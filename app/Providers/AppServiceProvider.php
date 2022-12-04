<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Model::preventLazyLoading(app()->isProduction());

        View::composer('app.nav', function ($view) {
            $categories = Category::withCount(['products' => function ($query) {
                $query->where('stock', '>', 0);
            }])
                ->orderBy('sort_order')
                ->orderBy('slug')
                ->get();

            $view->with([
               'categories' => $categories,
            ]);
        });
    }
}
