<?php
namespace Raysirsharp\LaravelLangCMS;

use Illuminate\Support\ServiceProvider;

class LaravelLangCMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Assets' => public_path('lang-cms'),
        ], 'public');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}