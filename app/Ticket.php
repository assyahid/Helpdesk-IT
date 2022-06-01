<?php namespace App;

use Carbon\Carbon;
use Common\Search\Searchable;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;

/**
 * App\Ticket
 *
 * @property integer $id
 * @property string $subject
 * @property integer $user_id
 * @property Carbon $closed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $created_at_formatted
 * @property string $created_at_month
 * @property integer $closed_by
 * @property integer $assigned_to
 * @property-read ?User $user
 * @property-read Collection|Tag[] $tags
 * @property-read Collection|Reply[] $replies
 * @mixin Eloquent
 * @property-read string $status
 * @property-read mixed $uploads_count
 * @property-read Collection|Tag[] $categories
 * @property-read Collection|Reply[] $latest_replies
 * @property-read Collection|Reply[] $notes
 * @property-read ?Reply $latest_reply
 * @property-read mixed $updated_at_formatted
 * @property-read Reply $latest_creator_reply
 * @property-read Reply $repliesCount
 * @property-read User $assignee
 * @property string $received_at_email
 * @method whereStatus(string $status, ?string $operator)
 * @property string|null $email_id
 * @property-read int|null $categories_count
 * @property-read string $model_type
 * @property-read int|null $latest_replies_count
 * @property-read int|null $notes_count
 * @property-read int|null $replies_count
 * @property-read int|null $tags_count
 * @method static Builder|Ticket matches(array $columns, string $value)
 * @method static Builder|Ticket mysqlSearch(string $query)
 * @method static Builder|Ticket newModelQuery()
 * @method static Builder|Ticket newQuery()
 * @method static Builder|Ticket orderByStatus()
 * @method static Builder|Ticket query()
 */
class Ticket extends Model
{
    use Searchable;

    const MODEL_TYPE = 'ticket';

    protected $guarded = ['id', 'animated'];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'assigned_to' => 'integer',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'closed_at'];
    protected $appends = ['updated_at_formatted', 'model_type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->where(
            'tags.type',
            'category',
        );
    }

    public function replies(): HasMany
    {
        return $this->hasMany('App\Reply')->orderBy('created_at', 'desc');
    }

    public function repliesCount(): HasOne
    {
        return $this->hasOne(Reply::class)
            ->selectRaw('ticket_id, count(*) as aggregate')
            ->groupBy('ticket_id');
    }

    /**
     * One to many relationship with Reply model. Returns only 5 latest replies.
     *
     * @return hasMany
     */
    public function latest_replies()
    {
        return $this->hasMany(Reply::class)
            ->where('type', Reply::REPLY_TYPE)
            ->orderBy('created_at', 'desc')
            ->limit(5);
    }

    public function latest_reply(): HasOne
    {
        return $this->hasOne(Reply::class)
            ->where('type', Reply::REPLY_TYPE)
            ->orderBy('created_at', 'desc');
    }

    /**
     * One to many relationship with Reply model (filtered to notes only).
     *
     * @return hasMany
     */
    public function notes()
    {
        return $this->hasMany(Reply::class)
            ->orderBy('created_at', 'desc')
            ->where('type', Reply::NOTE_TYPE);
    }

    public function scopeOrderByStatus(Builder $query)
    {
        $prefix = DB::getTablePrefix();
        return $query->orderByRaw(
            "FIELD(status, 'open', 'pending', 'closed', 'locked', 'spam') asc, {$prefix}tickets.updated_at desc",
        );
    }

    public function getUpdatedAtFormattedAttribute()
    {
        if (isset($this->attributes['updated_at'])) {
            return (new Carbon(
                $this->attributes['updated_at'],
            ))->diffForHumans();
        }
    }

    protected function scopeWhereTag(
        Builder $builder,
        string $tag,
        string $operator = '='
    ): Builder {
        return $builder->whereHas('tags', function (Builder $tb) use (
            $tag,
            $operator
        ) {
            $tb->where('name', $operator, $tag);
        });
    }

    public function scopeWhereStatus(
        Builder $builder,
        string $status,
        string $operator = '='
    ) {
        return $builder->whereTag($status, $operator);
    }

    public function getStatusAttribute(): ?string
    {
        // if tags are already loaded, use those records to avoid extra db query
        if ($this->relationLoaded('tags')) {
            $tag = Arr::first($this->tags, function ($tag) {
                return $tag['type'] === 'status';
            });

            // otherwise fetch status tag from db
        } else {
            $tag = $this->load('tags');
            return $this->getStatusAttribute();
        }

        return $tag ? $tag['name'] : null;
    }

    /**
     * Get number of uploads that are attached to this ticket.
     *
     * @param mixed $value
     * @return int
     */
    public function getUploadsCountAttribute($value)
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        return DB::table('file_entry_models')
            ->whereIn('model_id', function ($query) {
                /** @var $query Builder */
                return $query
                    ->from('replies')
                    ->where('replies.ticket_id', $this->id)
                    ->select('id');
            })
            ->where('model_type', Reply::class)
            ->count();
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'replies' => $this->replies->map(function (Reply $reply) {
                return $reply->toSearchableArray();
            }),
            'user' => $this->user ? $this->user->toSearchableArray() : null,
            'user_id' => $this->user ? $this->user->id : null,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'closed_at' => $this->closed_at->timestamp ?? '_null',
            'created_at' => $this->created_at->timestamp ?? '_null',
            'updated_at' => $this->updated_at->timestamp ?? '_null',
        ];
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['replies', 'user', 'tags']);
    }

    public static function filterableFields(): array
    {
        return [
            'id',
            'created_at',
            'updated_at',
            'closed_at',
            'assigned_to',
            'user_id',
            'status',
        ];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
