<?php

namespace App;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\SearchTerm
 *
 * @property int $id
 * @property string $term
 * @property Carbon|null $created_at
 * @property string $normalized_term
 * @property int $result_count
 * @property int $article_clicks
 * @property int $count
 * @property string $ctr
 * @property int $category_id
 * @property int $clicked_article
 * @property int $resulted_in_ticket
 * @mixin Eloquent
 * @method static Builder|SearchTerm newModelQuery()
 * @method static Builder|SearchTerm newQuery()
 * @method static Builder|SearchTerm query()
 */
class SearchTerm extends Model
{
    const UPDATED_AT = null;
    const MODEL_TYPE = 'searchTerm';

    /**
     * @var array
     */
    protected $guarded = ['id'];
    protected $appends = ['model_type'];

    protected $casts = [
        'id' => 'int',
        'resulted_in_ticket' => 'int',
        'count' => 'int',
        'clicked_article' => 'int',
        'ctr' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
