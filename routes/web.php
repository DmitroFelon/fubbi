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
            Route::get('read/{id?}', 'NotificationController@read');
            Route::get('', 'NotificationController@index');
        }
    );


    Route::namespace('Resources')->group(function () {

        Route::prefix('project')->group(
            function () {
                Route::group(['middleware' => ['role:admin|account_manager']], function () {
                    Route::get('accept_review/{project}', "ProjectController@accept_review");
                    Route::get('reject_review/{project}', "ProjectController@reject_review");
                });
                Route::get('apply_to_project/{project}', "ProjectController@apply_to_project");
                Route::get('apply_to_project/{project}', "ProjectController@apply_to_project");


                Route::get('decline_project/{project}', "ProjectController@decline_project");
            }
        );


        Route::prefix('projects')->group(function () {
            Route::post('{project}/prefill', 'ProjectController@prefill');
            Route::put('{project}/prefill', 'ProjectController@prefill');
            Route::post('{project}/prefill_files', 'ProjectController@prefill_files');
            Route::get('{project}/get_stored_files', 'ProjectController@get_stored_files');
            Route::get('{project}/remove_stored_file/{media}', 'ProjectController@remove_stored_files');
            Route::get('{project}/export', 'ProjectController@export');
            Route::get('{project}/resume', 'ProjectController@resume');
            Route::post('{project}/invite_users', 'ProjectController@invite_users');
            Route::post('{project}/invite_team', 'ProjectController@invite_team');

            Route::get('{project}/remove_from_project/{user}', 'ProjectController@remove_from_project');
            Route::get('{project}/remove_team_from_project/{team}', 'ProjectController@remove_team_from_project');
            Route::get('{project}/allow_modifications', 'ProjectController@allow_modifications');
        });

        Route::prefix('teams')->group(function () {
            Route::get('accept/{team}', 'TeamController@accept');
            Route::get('decline/{team}', 'TeamController@decline');
        });

        Route::prefix('messages')->group(function () {
            Route::get('read/{id}', 'MessageController@read');
            Route::get('user/{user}', 'MessageController@user');
            Route::get('clear', 'MessageController@clear');
        });

        Route::get('articles/batch_export', 'ArticlesController@batch_export');
        Route::get('articles/{article}/export/', 'ArticlesController@export');

        Route::prefix('articles')->group(function () {
            Route::get('request_access/{article}', 'ArticlesController@request_access');
        });

        Route::prefix('inspirations')->group(function () {
            Route::get('{id}/getFiles/{collection}', 'InspirationController@getFiles');
            Route::post('{id}/storeFile/{collection}', 'InspirationController@storeFile');
            Route::delete('{id}/removeFile/{file_id}', 'InspirationController@removeFile');
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
                Route::post('{project}/articles/{article}/rate/', 'ArticlesController@rate');
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
