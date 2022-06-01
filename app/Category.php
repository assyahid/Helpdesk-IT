<?php namespace App;

use App\Traits\OrdersByPosition;
use Carbon\Carbon;
use Common\Search\Searchable;
use Common\Settings\Settings;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Category
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property integer $position
 * @method static \Illuminate\Database\Query\Builder|Category orderByPosition()
 * @mixin Eloquent
 * @property int $parent_id
 * @property bool $hidden
 * @property-read Collection|Article[] $articles
 * @property-read Collection|Category[] $children
 * @property-read Category $parent
 * @method static \Illuminate\Database\Query\Builder|Category rootOnly()
 * @property string|null $image
 * @property-read int|null $articles_count
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category query()
 * @property int $default
 * @property-read string $model_type
 * @method static Builder|Category matches(array $columns, string $value)
 * @method static Builder|Category mysqlSearch(string $query)
 */
class Category extends Model
{
    use Searchable, OrdersByPosition;

    const MODEL_TYPE = 'category';

    protected $hidden = ['pivot'];
    protected $guarded = ['id'];
    protected $appends = ['model_type'];
    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'position' => 'integer',
        'hidden' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderByPosition();
    }

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * @return BelongsToMany
     */
    public function articles()
    {
        $query = $this->belongsToMany(
            Article::class,
            'category_article',
        )->where('draft', false);

        [$col, $dir] = explode(
            '|',
            app(Settings::class)->get(
                'articles.default_order',
                'position|desc',
            ),
        );
        $col === 'position'
            ? $query->orderByPosition()
            : $query->orderBy($col, $dir);

        return $query;
    }

    public function scopeRootOnly(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->timestamp ?? '_null',
            'updated_at' => $this->updated_at->timestamp ?? '_null',
        ];
    }

    public static function filterableFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public function toNormalizedArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'model_type' => self::MODEL_TYPE,
        ];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
