<?php

namespace App;

use Carbon\Carbon;
use Common\Files\FileEntry;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Common\Tags\Tag as BaseTag;

/**
 * App\Tag
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $display_name
 * @property ?int $tickets_count
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|FileEntry[] $files
 * @property-read int|null $files_count
 * @property-read string $model_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket[] $tickets
 * @property-read \Illuminate\Database\Eloquent\Collection|FileEntry[] $uploads
 * @property-read int|null $uploads_count
 * @method static Builder|Tag matches(array $columns, string $value)
 * @method static Builder|Tag mysqlSearch(string $query)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 */
class Tag extends BaseTag
{
    /**
     * @return MorphToMany
     */
    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function uploads()
    {
        return $this->morphedByMany(FileEntry::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function categories()
    {
        return $this->morphedByMany(Category::class, 'taggable')
            ->select(['id', 'name']);
    }
}
