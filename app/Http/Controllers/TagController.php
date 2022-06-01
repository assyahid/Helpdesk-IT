<?php namespace App\Http\Controllers;

use App\Services\TagRepository;
use App\Tag;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    /**
     * @var Tag
     */
    private $tag;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(
        Request $request,
        Tag $tag,
        TagRepository $tagRepository
    ) {
        $this->tag = $tag;
        $this->request = $request;
        $this->tagRepository = $tagRepository;
    }

    public function tagsForAgentMailbox()
    {
        return $this->success([
            'tags' => $this->tagRepository->getStatusAndCategoryTags(),
        ]);
    }

    public function store()
    {
        $this->authorize('store', Tag::class);

        $this->validate(
            $this->request,
            $this->tagRepository->getValidationRules('store'),
        );

        $tag = $this->tagRepository->create($this->request->all());

        return $this->success(['tag' => $tag], 201);
    }
    public function update(int $id)
    {
        $tag = $this->tagRepository->findOrFail($id);

        $this->authorize($tag);

        $this->validate(
            $this->request,
            $this->tagRepository->getValidationRules('update', $tag->id),
        );

        $tag = $this->tagRepository->update($tag, $this->request->all());

        return $this->success(['tag' => $tag]);
    }

    public function destroy(string $ids)
    {
        $tagIds = explode(',', $ids);
        $this->authorize('destroy', [Tag::class, $tagIds]);

        $this->tagRepository->deleteMultiple($tagIds);

        return $this->success([], 204);
    }
}
