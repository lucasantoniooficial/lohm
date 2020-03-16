<?php

namespace Aposoftworks\LOHM\Providers;

use Illuminate\Support\ServiceProvider;

class LohmServiceProvider extends ServiceProvider {
    public function register () {
        //Merging
        $this->mergeConfigFrom(
            __DIR__.'/../config/lohm.php', 'lohm'
        );
    }

    public function boot () {
        //Publishing
        $this->publishes([
            __DIR__."/../config/lohm.php" => config_path("lohm")
        ]);

        $this->publishes([
            __DIR__."/../config/lohm.php" => config_path("lohm")
        ], "config");
    }
}
