<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\Facades\LOHM;

//Helpers
use Aposoftworks\LOHM\Classes\Helpers\NameBuilder;
use Aposoftworks\LOHM\Classes\Helpers\DirectoryHelper;

class MigrateCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lohm:migrate {--C|check}';

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
        $allphpfiles = collect(DirectoryHelper::listFiles(base_path()."/database/migrations/"));

        $allphpfiles->filter(function ($file) {
            return NameBuilder::isMigration($file);
        });

        //Add migrations to queue
        $allphpfiles->each(function ($file) {
            LOHM::queue($file);
        });

        //Run migrations
        LOHM::migrate();

        $this->info("Migrations runn successfully");
    }
}
