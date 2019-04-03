<?php

namespace Statamic\Addons\Blend;

use Statamic\Extend\ServiceProvider;

class BlendServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Blend::class);

        require_once(__DIR__.'/helpers.php');
    }
}
