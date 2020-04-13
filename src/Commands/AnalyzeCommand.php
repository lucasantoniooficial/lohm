<?php

namespace Aposoftworks\LOHM\Commands;

//Laravel
use Illuminate\Console\Command;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;
use Aposoftworks\LOHM\Classes\Virtual\VirtualDatabase;

class AnalyzeCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lohm:analyze {database} {table?} {column?} {--raw}';

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
     * @param  \App\DripEmailer  $drip
     * @return mixed
     */
    public function handle() {
        if (is_null($this->argument("table"))) {
            dd(VirtualDatabase::fromDatabase($this->argument("database")));
        }
        else if (is_null($this->argument("column"))) {
            dd(VirtualTable::fromDatabase($this->argument("database"), $this->argument("table")));
        }
        else {
            dd(VirtualColumn::fromDatabase($this->argument("database"), $this->argument("table"), $this->argument("column")));
        }
    }
}
