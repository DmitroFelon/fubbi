<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//stripe and thrivecard hooks
Route::namespace('Webhooks')->group(function () {
    //Stripe routes
    Route::post('stripe/webhook', 'WebhookController@handleWebhook');
    //Thrivecart handler
    Route::post('thrivecart', 'TrivecartController@handle');

    Route::get('cart_redirect', 'TrivecartController@cartRedirect');
});

//Auth
Auth::routes();

//hide closed alrets
Route::get('coockie/{key}/{value}', function (string $key, string $value) {
    Session::put($key, $value);
    return ['ok'];
});


//shows rendered emails
Route::get('/test', function () {
    
});

//shows rendered emails
Route::get('/test_email/{index}', function ($index) {

    try {
        $auth_user                = \App\User::first();
        $demo_user                = \App\User::first();
        $demo_project             = \App\Models\Project::first();
        $demo_article             = (!is_null($demo_project)) ? $demo_project->articles()->first() : null;
        $demo_invitations_team    = \App\Models\Invite::teams()->first();
        $demo_invitations_project = \App\Models\Invite::projects()->first();
        $notifications            = [
            ($demo_user) ? new \App\Notifications\Client\Registered($demo_user) : null,
            ($demo_project) ? new \App\Notifications\Worker\Attached($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Worker\Detached($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Worker\Progress($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Project\Delayed($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Project\Filled($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Project\Remind($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Project\Removed($demo_project) : null,
            ($demo_project) ? new \App\Notifications\Project\Subscription($demo_project) : null,

            ($demo_project) ? new \App\Notifications\Project\WillRemoved($demo_project) : null,

            ($demo_invitations_project) ? new \App\Notifications\Project\Invite($demo_invitations_project) : null,
            ($demo_invitations_team) ? new \App\Notifications\Team\Invite($demo_invitations_team) : null,

            ($demo_article) ? new \App\Notifications\Project\ThirdArticleReject($demo_article) : null,
            ($demo_article) ? new \App\Notifications\Article\Approval($demo_article) : null,
        ];
        if (!isset($notifications[$index - 1])) {
            return 'Undefined notification';
        }
        $n        = $notifications[$index - 1];
        $mailable = new \App\Mail\TestingEmail($n->toMail($auth_user));
        return $mailable;

    } catch (\Exception $e) {
        return $e->getMessage();
    }


});

//Socket auth
Broadcast::routes();

Broadcast::channel('App.User.{user_id}', function ($user, $user_id) {
    return true;
});

//Notifications
Broadcast::channel('conversation.{conversation_id}', function ($user, $conversation_id) {
    $conversation = \Musonza\Chat\Facades\ChatFacade::conversation($conversation_id);
    if (!$conversation) {
        return false;
    }
    return ($conversation->users()->where('id', $user->id))
        ? ['id' => $user->id, 'name' => $user->name] : false;
});

//Main routes group
Route::middleware(['auth'])->group(function () {

    Route::group(['middleware' => ['role:admin']], function () {

        Route::get('charges', ['uses' => 'ChargesController@index', 'as' => 'charges']);

        Route::get('dashboard', ['uses' => 'DashboardController@dashboard', 'as' => 'dashboard']);
        Route::get('dashboard/preload/{view}', ['uses' => 'DashboardController@preloadView', 'as' => 'preloadView']);

        Route::namespace('Resources')->group(function () {
            Route::resources([
                'plans'       => 'PlanController',
                'help_videos' => 'HelpVideosController'
            ]);
        });
    });

    Route::get('dashboard', ['uses' => 'DashboardController@dashboard', 'as' => 'dashboard']);
    Route::get('dashboard/preload/{view}', ['uses' => 'DashboardController@preloadView', 'as' => 'preloadView']);

    Route::prefix('notification')->group(
        function () {
            Route::get('show/{id}', 'NotificationController@show');
            Route::get('read/{id?}', 'NotificationController@read')->name('notification.read.id');
            Route::get('read_all', 'NotificationController@readAll')->name('notification.read.all');
            Route::get('', 'NotificationController@index');
            Route::get('messages', 'NotificationController@indexMessages');
        }
    );

    Route::prefix('invite')->group(function() {
        Route::get('team/{team}/accept', 'InviteController@acceptTeamInvite')->name('accept.team.invite');
        Route::get('team/{team}/decline', 'InviteController@declineTeamInvite')->name('decline.team.invite');
        Route::get('project/{project}/accept', 'InviteController@acceptProjectInvite')->name('accept.project.invite');
        Route::get('project/{project}/decline', 'InviteController@declineProjectInvite')->name('decline.project.invite');
    });


    Route::prefix('files')->group(function () {
        Route::get('project/{project}', 'FileController@storeProjectFilesForm')->name('form.project.files');
        Route::get('project/{project}/article/{article}', 'FileController@storeArticleFilesForm')->name('form.article.files');
        Route::prefix('store')->group(function () {
            Route::post('article/{article}/{collection}', 'FileController@storeArticleFiles')->name('store.article.files');
            Route::post('project/{project}/{collection}', 'FileController@storeProjectFiles')->name('store.project.files');
            Route::post('inspiration/{inspiration}/{collection}', 'FileController@storeInspirationFiles')->name('store.inspiration.files');
        });
        Route::prefix('get')->group(function () {
            Route::get('article/{article}/{collection}', 'FileController@getArticleFiles')->name('get.article.files');
            Route::get('project/{project}/{collection}', 'FileController@getProjectFiles')->name('get.project.files');
            Route::get('inspiration/{inspiration}/{collection}', 'FileController@getInspirationFiles')->name('get.inspiration.files');
        });
        Route::prefix('delete')->group(function () {
            Route::delete('article/{article}/{fileId}', 'FileController@deleteArticleFile')->name('delete.article.files');
            Route::delete('project/{project}/{fileId}', 'FileController@deleteProjectFile')->name('delete.project.files');
            Route::delete('inspiration/{inspiration}/{fileId}', 'FileController@deleteInspirationFile')->name('delete.inspiration.files');
        });
    });

    Route::prefix('export')->group(function () {
        Route::get('project/{project}', 'FileController@exportProject')->name('export.project');
        Route::get('article/{article}', 'FileController@exportArticle')->name('export.article');
        Route::get('articles', 'FileController@exportArticles')->name('export.articles');
    });

    Route::prefix('projects')->group(function () {
        Route::post('{project}/attach_user', 'ProjectWorkersController@attachWorkers')->name('project.attach.workers');
        Route::post('{project}/attach_team', 'ProjectWorkersController@attachTeam')->name('project.attach.team');
        Route::get('{project}/detach_user/{id}', 'ProjectWorkersController@detachWorkers')->name('project.detach.worker');
        Route::get('{project}/detach_team/{id}', 'ProjectWorkersController@detachTeam')->name('project.detach.team');

    });

    Route::namespace('Resources')->group(function () {

        Route::prefix('project')->group(
            function () {
                Route::group(['middleware' => ['role:admin|account_manager']], function () {
                    Route::get('accept_review/{project}', "ProjectController@acceptReview");
                    Route::get('reject_review/{project}', "ProjectController@rejectReview");
                });
            }
        );


        Route::prefix('projects')->group(function () {
            Route::match(['post', 'put'], '{project}/prefill', 'ProjectController@prefill');
            Route::post('{project}/prefill_files', 'ProjectController@prefill_files')->name('add.files');
            Route::get('{project}/get_stored_files', 'ProjectController@get_stored_files');
            Route::get('{project}/remove_stored_file/{media}', 'ProjectController@remove_stored_files');
            Route::get('{project}/resume', 'ProjectController@resume');
            Route::get('{project}/allow_modifications', 'ProjectController@allow_modifications');
        });

        Route::prefix('messages')->group(function () {
            Route::get('read/{id}', 'MessageController@read');
            Route::get('user/{user}', 'MessageController@user');
            Route::get('clear', 'MessageController@clear');
        });

        Route::prefix('articles')->group(function () {
            Route::get('request_access/{article}', 'ArticlesController@request_access');
        });

        Route::resources(
            [
                'projects'     => 'ProjectController',
                'messages'     => 'MessageController',
                'users'        => 'UserController',
                'teams'        => 'TeamController',
                'issues'       => 'IssueController',
                'articles'     => 'ArticlesController',
                'inspirations' => 'InspirationController'
            ]
        );


    });

    Route::prefix('ideas')->group(function () {
        Route::post('{idea}/prefill_meta_files', 'IdeaController@prefill_meta_files');
        Route::get('{idea}/get_stored_idea_files', 'IdeaController@get_stored_idea_files');
        Route::get('{idea}/remove_stored_file/{media}', 'IdeaController@remove_stored_files');
        Route::get('{idea}', 'IdeaController@show');
        Route::post('{idea}/fill', 'IdeaController@update')->name('updateIdea');
    });

    Route::namespace('Project')->group(
        function () {
            Route::prefix('project')->group(function () {
                Route::get('{project}/articles/{article}/accept', 'ArticlesController@accept');
                Route::get('{project}/articles/{article}/decline', 'ArticlesController@decline');
                Route::post('{project}/articles/{article}/save_social_posts', 'ArticlesController@save_social_posts');
                Route::post('articles/{article}/rate/', 'ArticlesController@rate');
            });

            Route::resources([
                'project.articles' => 'ArticlesController',
                'project.plan'     => 'PlanController',
            ]);
        }
    );

    Route::post('subscribe', 'SubscriptionController');

    Route::get('research', 'ResearchController@index');
    Route::get('research/load', 'ResearchController@load');

    Route::prefix('settings')->group(function () {
        Route::post('billing', 'SettingsController@billing');
        Route::post('', 'SettingsController@save');
        Route::get('', ['as' => 'settings', 'uses' => 'SettingsController@index']);
    });

    Route::post('/email_reset', 'Auth\ResetEmailController@sendEmail')->name('sendEmail');
    Route::get('/email_reset/{token}', 'Auth\ResetEmailController@reset')->name('resetEmail');

    Route::get('/{page?}/{action?}/{id?}', 'DashboardController@index');

});
