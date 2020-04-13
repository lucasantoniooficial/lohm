<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\CreateNewTable;
use Aposoftworks\LOHM\Classes\CreateNewVersion;

use Aposoftworks\LOHM\Classes\Concrete\ConcreteTable;

class NewCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:table {name : The name of the table to be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new table to be used inside of your migrations';

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
     * @return mixed
     */
    public function handle() {
        if (CreateNewTable::create($this->arguments(), $this->options())) {
            $this->info("Table created successfully");
        }
        else {
            $this->warn("Table already exists");
        }
    }
}
