<?php namespace App\Services\HelpCenter;

use App\Article;
use App\ArticleFeedback;
use App\Services\TagRepository;
use App\Tag;
use DB;

class ArticleRepository
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var ArticleFeedback
     */
    private $feedback;

    public function __construct(
        Article $article,
        TagRepository $tagRepository,
        ArticleFeedback $feedback
    ) {
        $this->article = $article;
        $this->feedback = $feedback;
        $this->tagRepository = $tagRepository;
    }

    public function create(array $params): Article
    {
        $article = $this->article->create([
            'title' => $params['title'],
            'body' => $params['body'],
            'slug' => $params['slug'] ?? null,
            'description' => $params['description'] ?? null,
            'draft' => $params['draft'] ?? 0,
        ]);

        $article->categories()->attach($params['categories']);

        if (!is_null($params['uploads'])) {
            $article->uploads()->sync($params['uploads']);
        }

        if (isset($params['tags'])) {
            $tags = app(Tag::class)->insertOrRetrieve($params['tags']);
            $article->tags()->sync($tags->pluck('id'));
        }

        return $article;
    }

    public function update(int $id, array $params): Article
    {
        $article = $this->article->findOrFail($id);

        $article->fill([
            'title' => $params['title'],
            'body' => $params['body'],
            'slug' => $params['slug'] ?? null,
            'description' => $params['description'] ?? null,
            'draft' => $params['draft'] ?? 0,
            'position' => $params['position'] ?? 0,
        ]);

        $article->save();

        if (isset($params['categories'])) {
            $article->categories()->sync($params['categories']);
        }

        if (!is_null($params['uploads'])) {
            $article->uploads()->sync($params['uploads']);
        }

        if (isset($params['tags'])) {
            $tags = app(Tag::class)->insertOrRetrieve($params['tags']);
            $article->tags()->sync($tags->pluck('id'));
        }

        return $article;
    }

    /**
     * Delete specified help center articles.
     *
     * @param integer[] $ids
     * @return int
     */
    public function deleteMultiple($ids)
    {
        //detach categories
        DB::table('category_article')
            ->whereIn('article_id', $ids)
            ->delete();

        //detach tags
        DB::table('taggables')
            ->whereIn('taggable_id', $ids)
            ->where('taggable_type', Article::class)
            ->delete();

        //delete articles
        $this->article->whereIn('id', $ids)->delete();

        return count($ids);
    }

    public function submitFeedback(array $params)
    {
        //if we are not able to resolve user ip and user is not logged in, bail
        if (!$params['user_id'] && !$params['ip']) {
            return 0;
        }

        $article = $this->article->findOrFail($params['article_id']);

        //if we have user_id, search for existing feedback by user_id
        if ($params['user_id']) {
            $feedback = $article
                ->feedback()
                ->where('user_id', $params['user_id'])
                ->first();
        }

        //if we didn't find feedback by user_id and have client IP, search for existing feedback by client IP
        if (!isset($feedback) && $params['ip']) {
            $feedback = $article
                ->feedback()
                ->where('ip', $params['ip'])
                ->first();
        }

        if (!$feedback) {
            $feedback = $this->feedback->newInstance();
        }

        $feedback->fill($params)->save();
    }
}
