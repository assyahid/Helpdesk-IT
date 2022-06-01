<?php

namespace App\Http\Controllers;

use App\Article;
use App\FileEntry;
use Common\Core\BaseController;
use Common\Files\Response\DownloadFilesResponse;

class ArticleAttachmentsController extends BaseController
{
    /**
     * @var FileEntry
     */
    private $fileEntry;

    public function __construct(FileEntry $fileEntry)
    {
        $this->fileEntry = $fileEntry;
    }

    public function download(Article $article, $hashes)
    {
        $this->authorize('show', $article);

        $hashes = explode(',', $hashes);
        $fileEntryIds = array_map(function ($hash) {
            return $this->fileEntry->decodeHash($hash);
        }, $hashes);

        $fileEntries = $article
            ->uploads()
            ->whereIn('file_entries.id', $fileEntryIds)
            ->get();

        if ($fileEntries->isEmpty()) {
            abort(404);
        }

        return app(DownloadFilesResponse::class)->create($fileEntries);
    }
}
