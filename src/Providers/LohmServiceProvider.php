<?php

namespace Aposoftworks\LOHM\Providers;

//General
use Illuminate\Support\ServiceProvider;

//Commands
use Aposoftworks\LOHM\Commands\NewCommand;
use Aposoftworks\LOHM\Commands\DiffCommand;
use Aposoftworks\LOHM\Commands\ClearCommand;
use Aposoftworks\LOHM\Commands\MigrateCommand;
use Aposoftworks\LOHM\Commands\AnalyzeCommand;
use Aposoftworks\LOHM\Commands\CurrentCommand;

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
            __DIR__."/../config/lohm.php" => config_path("lohm.php")
        ], "lohm-config");

        $this->publishes([
            __DIR__."/../Stubs/table.stub.php" => resource_path("stubs/lohm.php")
        ], "lohm-stub");

        //Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                NewCommand::class,
                MigrateCommand::class,
                ClearCommand::class,
                AnalyzeCommand::class,
                DiffCommand::class,
                CurrentCommand::class,
            ]);
        }
    }
}
