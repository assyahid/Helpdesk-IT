<?php namespace App\Http\Controllers;

use App\CannedReply;
use Auth;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use DB;
use Illuminate\Http\Request;

class CannedRepliesController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $this->authorize('index', CannedReply::class);

        $builder = CannedReply::with('uploads');

        if ($userId = $this->request->get('userId')) {
            $builder->where('user_id', $userId);
        }

        if ($this->request->get('shared')) {
            $builder->orWhere('shared', true);
        }

        $datasource = new Datasource($builder, $this->request->all());

        return $this->success(['pagination' => $datasource->paginate()]);
    }

    public function store()
    {
        $this->authorize('store', CannedReply::class);

        $userId = Auth::id();

        $this->validate($this->request, [
            'body' => 'required|string|min:3',
            'shared' => 'required|boolean',
            'name' =>
                'required|string|min:3|max:255|unique:canned_replies,name,NULL,id,user_id,' .
                $userId,
            'uploads' => 'array|max:5|exists:file_entries,id',
        ]);

        $cannedReply = CannedReply::create([
            'body' => $this->request->get('body'),
            'name' => $this->request->get('name'),
            'user_id' => $userId,
        ]);

        if ($uploads = $this->request->get('uploads')) {
            $cannedReply->uploads()->sync($uploads);
        }

        return $this->success(['cannedReply' => $cannedReply], 201);
    }

    public function update(int $id)
    {
        $cannedReply = CannedReply::findOrFail($id);

        $this->authorize('update', $cannedReply);

        $userId = Auth::id();

        $this->validate($this->request, [
            'body' => 'required|string|min:3',
            'shared' => 'boolean',
            'name' => "required|string|min:3|max:255|unique:canned_replies,name,$id,id,user_id,$userId",
            'uploads' => 'array|max:5',
            'uploads.*' => 'int|min:10',
        ]);

        $cannedReply->fill($this->request->except('uploads'))->save();
        $cannedReply->uploads()->sync($this->request->get('uploads'));

        return $this->success(['cannedReply' => $cannedReply], 201);
    }

    public function destroy(string $ids)
    {
        $replyIds = explode(',', $ids);
        $this->authorize('destroy', CannedReply::class);

        // detach uploads from canned replies
        DB::table('file_entry_models')
            ->where('model_type', CannedReply::class)
            ->whereIn('model_id', $replyIds)
            ->delete();

        CannedReply::whereIn('id', $replyIds)->delete();

        return $this->success();
    }
}
