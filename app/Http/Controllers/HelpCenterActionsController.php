<?php namespace App\Http\Controllers;

use App\Services\HelpCenter\Actions\ExportHelpCenter;
use App\Services\HelpCenter\Actions\ExportHelpCenterImages;
use App\Services\HelpCenter\Actions\ImportHelpCenter;
use Auth;
use Illuminate\Http\Request;
use Common\Core\BaseController;
use Storage;
use Symfony\Component\HttpFoundation\Response;

class HelpCenterActionsController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('isAdmin');
        $this->request = $request;
    }

    public function deleteUnusedImages()
    {
        $names = app(ExportHelpCenterImages::class)->execute();

        $files = Storage::disk('public')->files('article-images');
        $toDelete = array_diff($files, $names->toArray());

        Storage::disk('public')->delete($toDelete);

        return $this->success();
    }

    public function export()
    {
        $filename = app(ExportHelpCenter::class)->execute(
            $this->request->get('format', 'json'),
        );

        return response(file_get_contents($filename), 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="hc-export.zip',
        ]);
    }

    public function import(Request $request)
    {
        $path = $request->file('data')->path();
        app(ImportHelpCenter::class)->execute($path);
        return $this->success();
    }
}
