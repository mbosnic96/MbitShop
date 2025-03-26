<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Brand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('navigation-menu', function ($view) {
            $categories = Category::orderBy('position', 'asc')->get()->groupBy('parent_id');
            $view->with('categories', $categories);
        });

       
    }

}
