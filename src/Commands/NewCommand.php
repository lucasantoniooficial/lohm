<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\CreateNewTable;
use Aposoftworks\LOHM\Classes\CreateNewVersion;

class NewCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lohm:new {type} {name? : The name (with the path or not) of the type to be created} {--u|unify} {--versiony}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new table|version to be used inside of your migrations';

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
        $type = $this->argument("type");

        switch ($type) {
            case "table":
                if (CreateNewTable::create($this->arguments(), $this->options())) {
                    $this->info("Table created successfully");
                }
                else {
                    $this->warn("Table already exists for this version");
                }
            break;
            case "version":
                CreateNewVersion::create($this->arguments(), $this->options());
                $this->info("Version created successfully");
            break;
        }
    }
}
