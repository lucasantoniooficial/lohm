<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

//Classes
use Aposoftworks\LOHM\Classes\Facades\LOHM;

//Helpers
use Aposoftworks\LOHM\Classes\Helpers\NameBuilder;
use Aposoftworks\LOHM\Classes\Helpers\DirectoryHelper;

class ClearCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lohm:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the down method in every migration';

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
            LOHM::queue($file, "down");
        });

        //Visual bar
        $this->line("");
        $bar = $this->output->createProgressBar(count($allphpfiles));
        $bar->start();

        //Run migrations
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        LOHM::migrate()->each(function ($migration) use ($bar) {
            $migration();
            $bar->advance();
        });
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        //Finish
        $bar->finish();
        $this->line("");
        $this->line("");
        $this->info("Migrations cleared successfully");
    }
}
