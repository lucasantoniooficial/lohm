<?php

namespace Aposoftworks\LOHM\Classes;

use Aposoftworks\LOHM\Classes\Concrete\ConcreteTable;
use Illuminate\Support\Facades\DB;

class LOHM {
    protected $connection;

    protected $queues        = [];
    protected $latequeues    = [];

    //-------------------------------------------------
    // Main methods
    //-------------------------------------------------

    public function __construct () {
        $this->connection = config("database.default");
    }

    //-------------------------------------------------
    // Create methods
    //-------------------------------------------------

    public function table ($name, $callback) {
        $table = new ConcreteTable($name);
        $callback($table);
        $this->queues[] = ["conn" => $this->connection, "query" => $table->toQuery()];
    }

    public function dropTable ($name) {
        $this->latequeues[] = ["conn" => $this->connection, "query" => "DROP TABLE ".$name];
    }

    public function conn ($name) {
        $this->connection = $name;
    }

    //-------------------------------------------------
    // Migration methods
    //-------------------------------------------------

    public function queue ($filepath, $method = "up") {
        //Generate name
        $classnamewithextension = class_basename($filepath);
        $classname = preg_replace("/\.php/", "", $classnamewithextension);

        //Actually require
        require $filepath;

        //Instanceit
        $class = new $classname();

        if($method === "up")
            $class->up();
        else
            $class->down();
    }

    public function migrate () {
        for ($i = 0; $i < count($this->queues); $i++) {
            $queue = $this->queues[$i];

            DB::connection($queue["conn"])->statement($queue["query"]);
        }
    }
}
