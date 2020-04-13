<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\Facades\LOHM;

//Helpers
use Aposoftworks\LOHM\Classes\Helpers\NameBuilder;
use Aposoftworks\LOHM\Classes\Helpers\DirectoryHelper;
use Aposoftworks\LOHM\Classes\Helpers\QueryHelper;

class MigrateCommand extends Command {
    protected $migrationscreate = 0;
    protected $migrationsupdate = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the current version into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \App\DripEmailer  $drip
     * @return mixed
     */
    public function handle() {
        $allphpfiles = collect(DirectoryHelper::listFiles(config("lohm.default_table_directory")));

        $allphpfiles->filter(function ($file) {
            return NameBuilder::isMigration($file);
        });

        $this->line("");
        $this->line("Queuing migrations");
        $this->line("");
        $bar = $this->output->createProgressBar(count($allphpfiles));
        $bar->start();

        //Add migrations to queue
        $allphpfiles->each(function ($file) use ($bar) {
            LOHM::queue($file);
            $bar->advance();
        });

        $bar->finish();
        $this->line("");
        $this->line("");
        $this->line("Running migrations");
        $this->line("");
        $bar = $this->output->createProgressBar(count($allphpfiles));
        $bar->start();

        //Run migrations
        LOHM::migrate()->each(function ($migration) use ($bar) {
            $update = $migration();

            if ($update) $this->migrationsupdate += 1;
            else         $this->migrationscreate += 1;

            $bar->advance();
        });

        $bar->finish();
        $this->line("");
        $this->line("");
        $this->info("Migrations ran successfully");
        $this->line($this->migrationscreate." table".($this->migrationscreate == 1 ? "":"s")." were created");
        $this->line($this->migrationsupdate." content".($this->migrationscreate == 1 ? "":"s")." updated inside of the tables");
    }
}
