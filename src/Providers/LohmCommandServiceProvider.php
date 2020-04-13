<?php

namespace Aposoftworks\LOHM\Providers;

//General
use Illuminate\Support\ServiceProvider;
use Aposoftworks\LOHM\Classes\Helpers\BuildCache;

//Commands
use Aposoftworks\LOHM\Commands\NewCommand;
use Aposoftworks\LOHM\Commands\ClearCommand;
use Aposoftworks\LOHM\Commands\QueryCommand;
use Aposoftworks\LOHM\Commands\AnalyzeCommand;
use Aposoftworks\LOHM\Commands\MigrateCommand;
use Aposoftworks\LOHM\Commands\RecacheCommand;
use Aposoftworks\LOHM\Commands\DecacheCommand;

class LohmCommandServiceProvider extends ServiceProvider {
    public function boot () {
        if ($this->app->runningInConsole()) {
            //Initialize cache check
            BuildCache::initialize();

            $this->commands([
                NewCommand::class,
                MigrateCommand::class,
                ClearCommand::class,
                //RecacheCommand::class,
                //DecacheCommand::class,
                AnalyzeCommand::class,
                QueryCommand::class,
            ]);

            //Finish the cache and save everything
            //BuildCache::initialize();
        }
    }
}
