<?php namespace App;

use App\Services\HelpCenter\AddIdToAllHtmlHeadings;
use App\Traits\OrdersByPosition;
use Carbon\Carbon;
use Common\Files\FileEntry;
use Common\Search\Searchable;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Article
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property boolean $draft
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $visibility
 * @property integer $views
 * @property-read Collection|Tag[] $tags
 * @property-read Collection|ArticleFeedback[] $feedback
 * @method static \Illuminate\Database\Query\Builder|Article filterByCategories($ids)
 * @method static \Illuminate\Database\Query\Builder|Article withCategories($categories)
 * @method static \Illuminate\Database\Query\Builder|Article orderByFeedback($direction = 'desc')
 * @mixin Eloquent
 * @property string $slug
 * @property string $description
 * @property-read Collection|Category[] $categories
 * @property int $position
 * @method static \Illuminate\Database\Query\Builder|Article filterByTags($names)
 * @method static \Illuminate\Database\Query\Builder|Article orderByPosition()
 * @property-read Collection|FileEntry[] $uploads
 * @property-read int|null $categories_count
 * @property-read int|null $feedback_count
 * @property-read int|null $tags_count
 * @property-read int|null $uploads_count
 * @property int $positive_votes
 * @property int $negative_votes
 * @property ?float $score
 * @method Builder|Article newModelQuery()
 * @method Builder|Article newQuery()
 * @method Builder|Article query()
 * @property string|null $extra_data
 * @property-read string $model_type
 * @method static Builder|Article matches(array $columns, string $value)
 * @method static Builder|Article mysqlSearch(string $query)
 */
class Article extends Model
{
    use Searchable, OrdersByPosition;

    const MODEL_TYPE = 'article';

    protected $guarded = ['id'];
    protected $hidden = ['pivot'];
    protected $appends = ['model_type'];

    public static $orderFields = [
        'title',
        'draft',
        'visibility',
        'views',
        'was_helpful',
        'created_at',
        'updated_at',
        'position',
    ];

    protected $casts = [
        'id' => 'integer',
        'was_helpful' => 'integer',
        'position' => 'integer',
    ];

    public function setBodyAttribute($value)
    {
        if ($value) {
            $this->attributes['body'] = app(
                AddIdToAllHtmlHeadings::class,
            )->execute($value);
        }
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'category_article',
            'article_id',
            'category_id',
        )->orderBy('parent_id', 'desc');
    }

    /**
     * Tags attached to this article.
     *
     * @return MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * @return belongsToMany
     */
    public function uploads()
    {
        return $this->morphToMany(
            FileEntry::class,
            'model',
            'file_entry_models',
        )->orderBy('file_entries.created_at', 'desc');
    }

    /**
     * User feedback attach to this article.
     *
     * @return HasMany
     */
    public function feedback()
    {
        return $this->hasMany(ArticleFeedback::class);
    }

    public function scopeFilterByCategories(Builder $query, $ids): Builder
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        return $query->whereHas('categories', function ($q) use ($ids) {
            $q->whereIn('categories.id', $ids);
        });
    }

    /**
     * @param Builder $query
     * @param mixed $names
     * @return mixed
     */
    public function scopeFilterByTags($query, $names)
    {
        if (!is_array($names)) {
            $names = explode(',', $names);
        }
        return $query->whereHas('tags', function ($q) use ($names) {
            $q->whereIn('tags.name', $names);
        });
    }

    /**
     * @param string|number[]|null $categories
     */
    public function scopeWithCategories(Builder $query, $categories): Builder
    {
        return $query->with([
            'categories' => function (BelongsToMany $q) use ($categories) {
                $q->with('parent');
                if ($categories) {
                    $q->whereHas('parent', function (Builder $q) use (
                        $categories
                    ) {
                        if (!is_array($categories)) {
                            $categories = explode(',', $categories);
                        }
                        $q->whereIn('id', $categories);
                    });
                }
            },
        ]);
    }

    /**
     * Order articles by the amount of 'was helpful' user
     * feedback they have in hc_article_feedback table.
     */
    public function scopeOrderByFeedback(
        Builder $query,
        string $direction = 'desc'
    ): Builder {
        $prefix = DB::getTablePrefix();
        $subQuery = "(SELECT count(*) FROM {$prefix}article_feedback WHERE was_helpful = 1 AND article_id = {$prefix}articles.id) as was_helpful";

        return $query
            ->select('*', DB::raw($subQuery))
            ->orderBy('was_helpful', $direction);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => strip_tags($this->body),
            'draft' => $this->draft,
            'description' => $this->description,
            'categories' => $this->categories->map->id,
            'created_at' => $this->created_at->timestamp ?? '_null',
            'updated_at' => $this->updated_at->timestamp ?? '_null',
        ];
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['categories']);
    }

    public static function filterableFields(): array
    {
        return ['id', 'created_at', 'updated_at', 'categories', 'draft'];
    }

    public function toNormalizedArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'image' => null,
            'model_type' => self::MODEL_TYPE,
        ];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
