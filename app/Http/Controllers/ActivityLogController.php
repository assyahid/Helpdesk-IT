<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Article;
use App\Ticket;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;
use Str;

class ActivityLogController extends BaseController
{
    /**
     * @var Activity
     */
    private $activity;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Activity $activity, Request $request)
    {
        $this->activity = $activity;
        $this->request = $request;
    }

    public function index()
    {
        $this->authorize('index', Activity::class);

        $builder = $this->activity->with(['subject']);

        if ($userId = $this->request->get('userId')) {
            $builder->where('causer_id', $userId);
        }

        $datasource = new Datasource($builder, $this->request->all());
        $datasource->order = ['col' => 'created_at', 'dir' => 'desc'];

        $pagination = $datasource->paginate();

        $pagination->transform(function (Activity $activity) {
            if ($activity->created_at->isToday()) {
                $day = __('today');
            } elseif ($activity->created_at->isYesterday()) {
                $day = __('yesterday');
            } elseif ($activity->created_at->gte(now()->subDays(7))) {
                $day = $activity->created_at->diffForHumans();
            } else {
                $day = $activity->created_at->format('Y-m-d');
            }
            $time = $activity->created_at->format('H:i');
            $activity->created_at_human = "$day • $time";

            if ($activity->subject->model_type === Ticket::MODEL_TYPE) {
                $activity->subject->subject = Str::limit($activity->subject->subject, 50);
            }
            if ($activity->subject->model_type === Article::MODEL_TYPE) {
                $activity->subject->makeHidden(['body']);
            }

            return $activity;
        });

        return $this->success(['pagination' => $pagination]);
    }

    public function store()
    {
        $this->authorize('store', Activity::class);

        $activityLog = app(CrupdateActivityLog::class)->execute(
            $request->all(),
        );

        return $this->success(['activityLog' => $activityLog]);
    }
}
