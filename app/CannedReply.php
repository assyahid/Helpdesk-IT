<?php namespace App;

use Carbon\Carbon;
use Common\Files\FileEntry;
use Common\Search\Searchable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\CannedReply
 *
 * @property integer $id
 * @property string $name
 * @property string $body
 * @property integer $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|FileEntry[] $uploads
 * @property-read User $user
 * @mixin Eloquent
 * @property bool $shared
 * @property-read int|null $uploads_count
 * @method static Builder|CannedReply newModelQuery()
 * @method static Builder|CannedReply newQuery()
 * @method static Builder|CannedReply query()
 * @property-read string $model_type
 * @method static Builder|CannedReply matches(array $columns, string $value)
 * @method static Builder|CannedReply mysqlSearch(string $query)
 */
class CannedReply extends Model
{
    use Searchable;

    const MODEL_TYPE = 'cannedReply';

    protected $guarded = ['id'];
    protected $appends = ['model_type'];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'shared' => 'boolean',
    ];

    public function uploads(): BelongsToMany
    {
        return $this->morphToMany(
            FileEntry::class,
            'model',
            'file_entry_models',
        )->orderBy('created_at', 'desc');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'body' => $this->body,
            'created_at' => $this->created_at->timestamp ?? '_null',
            'updated_at' => $this->updated_at->timestamp ?? '_null',
        ];
    }

    public static function filterableFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
