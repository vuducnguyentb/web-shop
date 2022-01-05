<?php

namespace App\Providers;

use App\Http\Views\Composers\CartComposer;
use App\Http\Views\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
//khai báo menu như sau
        View::composer('header',MenuComposer::class);
        View::composer('cart',CartComposer::class);

    }
}
