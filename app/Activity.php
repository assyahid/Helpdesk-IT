<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Common\Settings\Settings;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property-read Model $subject
 * @mixin Eloquent
 */
class Activity extends Model
{
    protected $table = 'activity_log';

    const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'properties' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    static function articleViewed(
        int $articleId,
        int $causerId,
        Carbon $createdAt
    ): ?Activity {
        return static::log(
            Article::class,
            $articleId,
            'viewed',
            $causerId,
            [],
            $createdAt,
        );
    }

    static function ticketCreated(Ticket $ticket): ?Activity
    {
        return static::log(
            Ticket::class,
            $ticket->id,
            'created',
            $ticket->user_id,
            [],
            $ticket->created_at,
        );
    }

    static function articlesSuggested(
        Ticket $ticket,
        string $query,
        array $articleIds,
        ?Carbon $createdAt
    ): ?Activity {
        return static::log(
            Ticket::class,
            $ticket->id,
            'articlesSuggested',
            $ticket->user_id,
            [
                'articleIds' => $articleIds,
                'query' => $query,
            ],
            $createdAt,
        );
    }

    static function replyCreated(Reply $reply, string $source): ?Activity
    {
        return static::log(
            Ticket::class,
            $reply->ticket_id,
            'replied',
            $reply->user_Id,
            [
                'replyId' => $reply->id,
                'source' => $source,
            ],
        );
    }

    static function helpCenterSearched(
        int $searchTermId,
        ?int $causerId
    ): ?Activity {
        return static::log(
            SearchTerm::class,
            $searchTermId,
            'searched',
            $causerId,
        );
    }

    private static function log(
        string $subjectType,
        int $subjectId,
        string $event,
        ?int $causerId,
        array $properties = null,
        ?Carbon $createdAt = null
    ): ?Activity {
        if (!app(Settings::class)->get('tickets.log_activity')) {
            return null;
        }
        $activity = (new static())->fill([
            'causer_id' => $causerId ?? Auth::id(),
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'event' => $event,
            'properties' => $properties,
            'created_at' => $createdAt ?? now(),
        ]);
        $activity->save();
        return $activity;
    }
}
