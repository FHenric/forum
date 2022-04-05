<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Channel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // * é uma forma de comaprtilhar essa função com todas as views que existirem no projeto, assim associando a $channels ao query Channel::all
        View::composer('*', function($view){
            $channels = Cache::rememberForever('channels', function () {
                return Channel::all();
            });
            $view->with('channels', $channels);
        });
    }

    
}
