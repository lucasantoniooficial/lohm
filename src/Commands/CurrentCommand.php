<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel

use Aposoftworks\LOHM\Classes\Helpers\DatabaseHelper;
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;
use Aposoftworks\LOHM\Classes\Virtual\VirtualDatabase;

class CurrentCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:current {database?} {table?} {column?} {--raw}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze data from the database';

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
    public function handle () {
        $this->line("");

        //From default connection
        if (is_null($this->argument("database")))
            return DatabaseHelper::printDatabase($this, VirtualDatabase::fromDatabase(config("database.connections.".config("database.default").".database")));

        //Specified by user
        if (is_null($this->argument("table")))
            return DatabaseHelper::printDatabase($this, VirtualDatabase::fromDatabase($this->argument("database")));

        if (is_null($this->argument("column")))
            return DatabaseHelper::printTable($this, VirtualTable::fromDatabase($this->argument("database"), $this->argument("table")));

        DatabaseHelper::printColumn(
            $this,
            VirtualColumn::fromDatabase (
                $this->argument("database"),
                $this->argument("table"),
                $this->argument("column")
            )
        );
    }
}
