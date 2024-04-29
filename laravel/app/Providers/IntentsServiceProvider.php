<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IntentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Read intents data from JSON file
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $intents = json_decode($json_data, true);

        // Share the intents data with all views
        view()->share('intents', $intents);
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
