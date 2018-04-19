<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 17/10/17
 * Time: 20:26
 */

namespace App\Providers;

use App\ViewComposers\LeftMenuComposer;
use App\ViewComposers\MasterComposer;
use App\ViewComposers\Pages\Admin\ArticlesByRate;
use App\ViewComposers\Pages\Admin\DeclinedArticlesComposer;
use App\ViewComposers\Pages\Admin\LastArticlesComposer;
use App\ViewComposers\Pages\Admin\LastChargesComposer;
use App\ViewComposers\Pages\Admin\OverdueArticles;
use App\ViewComposers\Pages\Admin\PendingChargesComposer;
use App\ViewComposers\Pages\Admin\RejectedChargesComposer;
use App\ViewComposers\TopMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewComposerServiceProvider
 *
 * @package App\Providers
 */
class ViewComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Navigation
         * */
        View::composer('layouts.navigation', LeftMenuComposer::class);
        /*
         * Notifications, helpers
         * */
        View::composer('master', MasterComposer::class);

        $this->adminDashboardComposer();
    }

    private function adminDashboardComposer()
    {
        View::composer('pages.admin.dashboard.widgets.articles.last', LastArticlesComposer::class);
        View::composer('pages.admin.dashboard.widgets.articles.declined', DeclinedArticlesComposer::class);
        View::composer('pages.admin.dashboard.widgets.articles.by_rate', ArticlesByRate::class);
        View::composer('pages.admin.dashboard.widgets.articles.overdue', OverdueArticles::class);
        View::composer('pages.admin.dashboard.widgets.charges.last', LastChargesComposer::class);
        View::composer('pages.admin.dashboard.widgets.charges.pending', PendingChargesComposer::class);
        View::composer('pages.admin.dashboard.widgets.charges.rejected', RejectedChargesComposer::class);
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}