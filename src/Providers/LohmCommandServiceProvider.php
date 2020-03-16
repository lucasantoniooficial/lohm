<?php

namespace Aposoftworks\LOHM\Providers;

//General
use Illuminate\Support\ServiceProvider;

//Commands
use Aposoftworks\LOHM\Commands\NewCommand;
use Aposoftworks\LOHM\Commands\MigrateCommand;
use Aposoftworks\LOHM\Commands\RecacheCommand;
use Aposoftworks\LOHM\Commands\DecacheCommand;

class LohmCommandServiceProvider extends ServiceProvider {
    public function register () {

    }

    public function boot () {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NewCommand::class,
                MigrateCommand::class,
                //RecacheCommand::class,
                //DecacheCommand::class,
            ]);
        }
    }
}
