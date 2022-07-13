<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
     
        
        view()->composer('frontend.includes.header', function ($view) {
            $view->with('menu', Menu::where(['status'=>1,'parent'=>0,'type'=>0])->orderBy('order_by','asc')->get());
        });
        
        // view()->composer('frontend.includes.footer', function ($view) {
                
        //     $view->with('menusFooter', Menu::where(['status'=>1,'parent'=>null,'type'=>1])->orderBy('order_by','asc')->get());
            
        // });

        // view()->composer('frontend.includes.sidebar', function ($view) {
          
        //     $view->with('postCategories', PostCategory::where(['status'=>1])->orderBy('name','asc')->get());
        //     // $view->with('postCategoryCount', PostCategory::withCount('post')->get());
        //     // $view->with('postCategoryCount', PostCategory::withCount('post')->get());
            
        // });


    }


}


