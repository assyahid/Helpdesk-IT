<?php namespace App\Http\Controllers;

use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Common\Core\BaseController;

class UserTagsController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Tag
     */
    private $tag;

    public function __construct(User $user, Tag $tag, Request $request)
    {
        $this->request = $request;
        $this->user = $user;
        $this->tag = $tag;
    }

    public function sync(int $userId)
    {
        $this->authorize('update', User::class);

        $this->validate($this->request, [
            'tags'   => 'array',
            'tags.*' => 'string'
        ]);

        $user = $this->user->findOrFail($userId);

        $tagIds = collect($this->request->get('tags'))->map(function($tagName) {
            return $this->tag->firstOrCreate(['name' => $tagName]);
        })->pluck('id');

        $user->tags()->sync($tagIds);

        return $this->success();
    }
}
