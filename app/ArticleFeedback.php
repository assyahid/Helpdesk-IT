<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\ArticleFeedback
 *
 * @property integer $id
 * @property boolean $was_helpful
 * @property string $comment
 * @property integer $hc_article_id
 * @property integer $user_id
 * @property string $ip
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Article $article
 * @mixin \Eloquent
 * @property int $article_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ArticleFeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ArticleFeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ArticleFeedback query()
 */
class ArticleFeedback extends Model
{
    protected $guarded = ['id'];

    protected $table = 'article_feedback';

    protected $casts = ['was_helpful' => 'integer'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
