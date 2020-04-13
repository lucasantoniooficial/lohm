<?php

namespace Aposoftworks\LOHM\Classes;

//Laravel
use Illuminate\Support\Facades\DB;

//Helpers
use Aposoftworks\LOHM\Classes\Helpers\QueryHelper;
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;
use Aposoftworks\LOHM\Classes\Concrete\ConcreteTable;
use Aposoftworks\LOHM\Classes\Helpers\DatabaseHelper;

class LOHM {
    protected $method;
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

        $this->enqueuer($table->toQuery(),      $table, "queues");
        $this->enqueuer($table->toLateQuery(),  $table, "latequeues");

        //Reset connection after change
        $this->connection = config("database.default");
    }

    public function dropTable ($name) {
        $this->latequeues[] = ["conn" => $this->connection, "query" => "DROP TABLE IF EXISTS ".$name, "type" => "alter"];
    }

    public function conn ($name) {
        $this->connection = $name;

        return $this;
    }

    public function existsTable ($name) {
        $exists = DB::connection($this->connection)->select(QueryHelper::checkTable($name));

        return count($exists) >= 1;
    }

    //-------------------------------------------------
    // Migration methods
    //-------------------------------------------------

    public function queue ($filepath, $method = "up") {
        $this->method = $method;

        //Generate name
        $classnamewithextension = class_basename($filepath);
        $classname              = preg_replace("/\.php/", "", $classnamewithextension);

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
        //dd($this->queues, $this->latequeues);

        //Merge the two queues and turn into a collection
        $queues = [];

        for ($i = 0; $i < count($this->queues); $i++) {
            $queue = $this->queues[$i];

            if (trim($queue["query"]) != "") {
                $queues[] = function () use ($queue) {
                    return $this->migrationRun($queue);
                };
            }
        }

        for ($i = 0; $i < count($this->latequeues); $i++) {
            $queue = $this->latequeues[$i];

            if (trim($queue["query"]) != "") {
                $queues[] = function () use ($queue) {
                    return $this->migrationRun($queue);
                };
            }
        }

        return collect($queues);
    }

    //-------------------------------------------------
    // Helper methods
    //-------------------------------------------------

    private function migrationRun ($data) {
        $tablename = $data["table"]->name();

        if ($data["type"] == "alter") {
            //Reset all indexes and foreign keys
            if (LOHM::conn($data["conn"])->existsTable($tablename)) {
                QueryHelper::dropConstraints($tablename);
                QueryHelper::dropIndexes($tablename);
            }

            //Insert all of it
            DB::connection($data["conn"])->statement($data["query"]);
            return true;
        }
        else {
            //Update table
            if (LOHM::conn($data["conn"])->existsTable($tablename)) {
                $connection     = config("database.connections.".$data["conn"].".database");
                $currenttable   = VirtualTable::fromDatabase($connection, $tablename);
                $changes        = DatabaseHelper::changesNeeded($currenttable, $data["table"]);

                if ($changes === "")
                    return true;
                else
                    DB::connection($data["conn"])->statement($changes);

                return true;
            }
            //Create table
            else {
                DB::connection($data["conn"])->statement($data["query"]);
                return true;
            }
        }
    }

    private function enqueuer ($queue, $table, $queryType = "queues") {
        $type = $queryType == "queues" ? "table":"alter";

        if (is_array($queue)) {
            for ($i = 0; $i < count($queue); $i++) {
                $this->$queryType[] = ["conn" => $this->connection, "query" => $queue[$i], "table" => $table, "type" => $type];
            }
        }
        else {
            $this->$queryType[] = ["conn" => $this->connection, "query" => $queue, "table" => $table, "type" => $type];
        }
    }
}
