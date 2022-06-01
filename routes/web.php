<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Controllers\ActionsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArticleAttachmentsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleFeedbackController;
use App\Http\Controllers\CannedRepliesController;
use App\Http\Controllers\CategoriesOrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConditionsController;
use App\Http\Controllers\EnvatoController;
use App\Http\Controllers\GmailWebhookController;
use App\Http\Controllers\HelpCenterActionsController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\NewTicketCategoriesController;
use App\Http\Controllers\OriginalReplyEmailController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchTermController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TicketAssigneeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketRepliesController;
use App\Http\Controllers\TicketsMailController;
use App\Http\Controllers\TicketsMergeController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TicketTagsController;
use App\Http\Controllers\TriggersController;
use App\Http\Controllers\TriggerValueOptionsController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\UserEmailsController;
use App\Http\Controllers\UserTagsController;
use Common\Notifications\NotificationSubscriptionsController;

Route::group(['prefix' => 'secure'], function () {
    //TICKETS
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('tickets/{tagId}/next-active-ticket', [TicketController::class, 'nextActiveTicket']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::put('tickets/{id}', [TicketController::class, 'update']);
    Route::post('tickets/merge/{ticket1}/{ticket2}', [TicketsMergeController::class, 'merge']);
    Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::delete('tickets/{ids}', [TicketController::class, 'destroy']);
    Route::get('tickets/{id}/replies', [TicketRepliesController::class, 'index']);
    Route::post('tickets/{id}/{type}', [TicketRepliesController::class, 'store'])->where('type', 'drafts|replies|notes');
    Route::post('tickets/assign', [TicketAssigneeController::class, 'change']);
    Route::post('tickets/status/change', [TicketStatusController::class, 'change']);
    Route::post('tickets/tags/add', [TicketTagsController::class, 'add']);
    Route::post('tickets/tags/remove', [TicketTagsController::class, 'remove']);

    //REPLIES
    Route::get('replies/{id}', [RepliesController::class, 'show']);
    Route::get('replies/{id}/original', [OriginalReplyEmailController::class, 'show']);
    Route::put('replies/{id}', [RepliesController::class, 'update']);
    Route::delete('replies/{id}', [RepliesController::class, 'destroy']);

    //USERS
    Route::post('users/{id}/tags/sync', [UserTagsController::class, 'sync']);
    Route::put('users/{id}/details', [UserDetailsController::class, 'update']);
    Route::post('users/{id}/emails/attach', [UserEmailsController::class, 'attach']);
    Route::post('users/{id}/emails/detach', [UserEmailsController::class, 'detach']);

    //SEARCH
    Route::get('search/all', [SearchController::class, 'all']);
    Route::get('search/users', [SearchController::class, 'users']);
    Route::get('search/tickets', [SearchController::class, 'tickets']);
    Route::get('search/articles', [SearchController::class, 'articles']);
    Route::post('search-term', [SearchTermController::class, 'storeSearchSession']);

    //TAGS
    Route::get('tags/agent-mailbox', [TagController::class, 'tagsForAgentMailbox']);
    Route::post('tags', [TagController::class, 'store']);
    Route::put('tags/{id}', [TagController::class, 'update']);
    Route::delete('tags/delete-multiple', [TagController::class, 'destroy']);

    //NEW TICKET CATEGORIES
    Route::get('new-ticket/categories', [NewTicketCategoriesController::class, 'index']);

    //REPORTS
    Route::get('reports/envato/earnings', [ReportsController::class, 'envatoEarnings']);
    Route::get('reports/tickets/range', [ReportsController::class, 'generateTicketsReport']);
    Route::get('reports/help-center', [ReportsController::class, 'helpCenterReport']);
    Route::get('reports/user/{userId}/searches', [ReportsController::class, 'userSearches']);

    //CANNED REPLIES
    Route::get('canned-replies', [CannedRepliesController::class, 'index']);
    Route::post('canned-replies', [CannedRepliesController::class, 'store']);
    Route::put('canned-replies/{id}', [CannedRepliesController::class, 'update']);
    Route::delete('canned-replies/{id}', [CannedRepliesController::class, 'destroy']);

    //HELP CENTER
    Route::get('help-center', [HelpCenterController::class, 'index']);
    Route::get('help-center/sidenav', [HelpCenterController::class, 'sidenav']);

    //HELP CENTER CATEGORIES
    Route::get('help-center/categories', [CategoryController::class, 'index']);
    Route::get('help-center/categories/{id}', [CategoryController::class, 'show']);
    Route::post('help-center/categories', [CategoryController::class, 'store']);
    Route::post('help-center/categories/reorder', [CategoriesOrderController::class, 'change']);
    Route::put('help-center/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('help-center/categories/{id}', [CategoryController::class, 'destroy']);

    //HELP CENTER ARTICLES
    Route::get('help-center/articles/{id}', [ArticleController::class, 'show']);
    Route::get('help-center/articles/{article}/download/{hashes}', [ArticleAttachmentsController::class, 'download']);
    Route::get('help-center/articles', [ArticleController::class, 'index']);
    Route::post('help-center/articles', [ArticleController::class, 'store']);
    Route::put('help-center/articles/{id}', [ArticleController::class, 'update']);
    Route::post('help-center/articles/{id}/feedback', [ArticleFeedbackController::class, 'submit']);
    Route::delete('help-center/articles/{id}', [ArticleController::class, 'destroy']);

    //TRIGGERS
    Route::get('triggers', [TriggersController::class, 'index']);
    Route::get('triggers/conditions', [ConditionsController::class, 'index']);
    Route::get('triggers/actions', [ActionsController::class, 'index']);
    Route::get('triggers/value-options/{name}', [TriggerValueOptionsController::class, 'show']);
    Route::get('triggers/{id}', [TriggersController::class, 'show']);
    Route::post('triggers', [TriggersController::class, 'store']);
    Route::put('triggers/{id}', [TriggersController::class, 'update']);
    Route::delete('triggers', [TriggersController::class, 'destroy']);

    //ENVATO
    Route::get('envato/validate-purchase-code', [EnvatoController::class, 'validateCode']);
    Route::post('envato/add-purchase-using-code', [EnvatoController::class, 'addPurchaseUsingCode']);
    Route::post('envato/items/import', [EnvatoController::class, 'importItems']);
    Route::post('users/{user}/envato/sync-purchases', [EnvatoController::class, 'syncPurchases']);

    //HElP CENTER IMPORT/EXPORT
    Route::post('help-center/actions/import', [HelpCenterActionsController::class, 'import']);
    Route::get('help-center/actions/export', [HelpCenterActionsController::class, 'export']);
    Route::post('help-center/actions/delete-unused-images', [HelpCenterActionsController::class, 'deleteUnusedImages']);

    //NOTIFICATIONS
    Route::apiResource('notification-subscription', NotificationSubscriptionsController::class);

    //ACTIVITY LOG
    Route::get('activity-log', [ActivityLogController::class, 'index']);
    Route::post('activity-log', [ActivityLogController::class, 'store']);
});

//TICKETS MAIL WEBHOOKS
Route::post('tickets/mail/incoming', [TicketsMailController::class, 'handleIncoming']);
Route::post('tickets/mail/incoming/gmail', [GmailWebhookController::class, 'handle']);
Route::post('tickets/mail/failed', [TicketsMailController::class, 'handleFailed']);

//FRONT-END ROUTES THAT NEED TO BE PRE-RENDERED
Route::get('/', 'HelpCenterController@index')->middleware('prerenderIfCrawler');
Route::get('help-center', 'HelpCenterController@index')->middleware('prerenderIfCrawler');
Route::get('help-center/articles/{articleId}/{slug}', 'ArticleController@show')->middleware('prerenderIfCrawler');
Route::get('help-center/articles/{parentId}/{articleId}/{slug}', 'ArticleController@show')->middleware('prerenderIfCrawler');
Route::get('help-center/articles/{parentId}/{childId}/{articleId}/{slug}', 'ArticleController@show')->middleware('prerenderIfCrawler');
Route::get('help-center/categories/{categoryId}/{slug}', 'CategoryController@show')->middleware('prerenderIfCrawler');
Route::get('help-center/search/{query}', 'SearchController@articles')->middleware('prerenderIfCrawler');

//CATCH ALL ROUTES AND REDIRECT TO HOME
Route::get('{all}', '\Common\Core\Controllers\HomeController@show')->where('all', '.*');
