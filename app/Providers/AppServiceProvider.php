<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Invite;
use App\Models\Project;
use App\Observers\ArticleObserver;
use App\Observers\InviteObserver;
use App\Observers\MessageObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use App\Services\Api\Keywords\KeywordsFactoryInterface;
use App\Services\Api\Keywords\LocalKeywords;
use App\Services\Api\KeywordTool;
use App\User;
use Form;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Musonza\Chat\Messages\Message;
use Psr\Log\LoggerInterface;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     */
    public function boot(UrlGenerator $url)
    {
        Schema::defaultStringLength(191);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $this->observers();

        $this->formComponents();

        if (App::environment('development')) {
            $url->forceScheme('https');
        }


    }

    /**
     * init veiw observers
     */
    private function observers()
    {
        Project::observe(ProjectObserver::class);
        Invite::observe(InviteObserver::class);
        User::observe(UserObserver::class);
        Message::observe(MessageObserver::class);
        Article::observe(ArticleObserver::class);
    }

    /**
     * init custom collective forms components
     */
    private function formComponents()
    {
        Form::component(
            'bsText',
            'components.form.text',
            [
                'name',
                'value'       => null,
                'label'       => null,
                'description' => null,
                'attributes'  => [],
                'type'        => 'text',
            ]
        );
        Form::component(
            'bsSelect',
            'components.form.select',
            [
                'name',
                'list',
                'selected'           => null,
                'label'              => null,
                'description'        => null,
                'select_attributes'  => [],
                'options_attributes' => [],
            ]
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!App::environment('development')) {
            $this->app->configureMonologUsing(function (Logger $monolog) {
                $processUser = posix_getpwuid(posix_geteuid());
                $processName = $processUser['name'];

                $filename = storage_path('logs/laravel-' . php_sapi_name() . '-' . $processName . '.log');
                $handler  = new RotatingFileHandler($filename);
                $monolog->pushHandler($handler);
            });
            $this->app->bind(KeywordsFactoryInterface::class, LocalKeywords::class);

        } else {
            $this->app->alias('bugsnag.logger', Log::class);
            $this->app->alias('bugsnag.logger', LoggerInterface::class);
            $this->app->bind(KeywordsFactoryInterface::class, KeywordTool::class);
        }


    }
}
