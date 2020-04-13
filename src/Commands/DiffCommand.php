<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel

use Aposoftworks\LOHM\Classes\Helpers\DatabaseHelper;
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;
use Aposoftworks\LOHM\Classes\Virtual\VirtualDatabase;

class DiffCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:diff {database?} {table?} {column?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze the differences between data from the database and the current migration';

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
    public function handle () {
        $this->line("");

        //From default connection
        if (is_null($this->argument("database")))
            return DatabaseHelper::diffDatabase($this, VirtualDatabase::fromDatabase(config("database.connections.".config("database.default").".database")));

        //Specified by user
        if (is_null($this->argument("table")))
            return DatabaseHelper::diffDatabase($this, VirtualDatabase::fromDatabase($this->argument("database")));

        if (is_null($this->argument("column")))
            return DatabaseHelper::diffTable($this, VirtualTable::fromDatabase($this->argument("database"), $this->argument("table")));

        DatabaseHelper::diffColumn(
            $this,
            VirtualColumn::fromDatabase (
                $this->argument("database"),
                $this->argument("table"),
                $this->argument("column")
            )
        );
    }
}
