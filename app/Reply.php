<?php namespace App;

use Carbon\Carbon;
use Common\Files\FileEntry;
use Common\Search\Searchable;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * App\Reply
 *
 * @property integer $id
 * @property string $body
 * @property integer $user_id
 * @property integer $ticket_id
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $created_at_formatted
 * @property-read Collection|FileEntry[] $uploads
 * @property-read ?Ticket $ticket
 * @property-read User $user
 * @method static \Illuminate\Database\Query\Builder|Reply compact()
 * @mixin Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Query\Builder|Reply whereUuid($value)
 * @property-read int|null $uploads_count
 * @method static Builder|Reply newModelQuery()
 * @method static Builder|Reply newQuery()
 * @method static Builder|Reply query()
 * @property string|null $email_id
 * @property int $user_Id
 * @method static Builder|Reply matches(array $columns, string $value)
 * @method static Builder|Reply mysqlSearch(string $query)
 */
class Reply extends Model
{
    use Searchable;

    const DRAFT_TYPE = 'drafts';
    const REPLY_TYPE = 'replies';
    const NOTE_TYPE = 'notes';
    const MODEL_TYPE = 'reply';

    const SOURCE_EMAIL = 'email';
    const SOURCE_SITE = 'site';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'ticket_id' => 'integer',
    ];

    protected $guarded = ['id', 'animated'];
    protected $appends = ['model_type'];
    protected $hidden = ['uuid', 'email_id'];

    public function uploads(): BelongsToMany
    {
        return $this->morphToMany(
            FileEntry::class,
            'model',
            'file_entry_models',
        )->orderBy('file_entries.created_at', 'desc');
    }

    /**
     * @return belongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompact(Builder $q)
    {
        return $q->select(
            'id',
            'user_id',
            DB::raw('SUBSTRING(body, 1, 80) as body'),
        );
    }

    public function stripBody(int $length = 200)
    {
        if ($this->exists) {
            $body = Str::limit(strip_tags($this->body, '<br>'), $length);
            $this->body = preg_replace('/<br\W*?>/', ' ', $body); // replace <br> with space
        }
    }

    public function bodyForEmail()
    {
        // prepend relative image urls for email body
        return preg_replace(
            '/"\/?(storage\/ticket_images\/[a-zA-Z0-9]+.[a-z]+)"/',
            url('') . "/$1",
            $this->body,
        );
    }

    public function getCreatedAtFormattedAttribute()
    {
        return (new Carbon($this->attributes['created_at']))->formatLocalized(
            '%b %e, %H:%M',
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'body' => strip_tags($this->body),
        ];
    }

    public static function filterableFields(): array
    {
        return ['id'];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
