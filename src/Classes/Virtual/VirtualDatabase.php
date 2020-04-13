<?php

namespace Aposoftworks\LOHM\Classes\Virtual;

//Interfaces
use Illuminate\Contracts\Support\Jsonable;
use Aposoftworks\LOHM\Contracts\ToRawQuery;
use Illuminate\Contracts\Support\Arrayable;
use Aposoftworks\LOHM\Contracts\ComparableVirtual;

//Facades
use Illuminate\Support\Facades\DB;

class VirtualDatabase implements ToRawQuery, ComparableVirtual, Jsonable, Arrayable {

    /**
     * The real name of this database
     *
     * @var string
     */
    protected $databasename;

    /**
     * The tables that compose this database
     *
     * @var \Aposoftworks\LOHM\Classes\Virtual\VirtualTable array
     */
    protected $tables;

    //-------------------------------------------------
    // Default methods
    //-------------------------------------------------

    public function __construct ($databasename, $tables = [], $valid = true) {
        $this->databasename = $databasename;
        $this->tables       = $tables;
        $this->isvalid      = $valid;
    }

    public function isValid () {
        return $this->isvalid;
    }

    //-------------------------------------------------
    // Import methods
    //-------------------------------------------------

    public static function fromDatabase ($databasename) {
        $tablesraw      = collect(DB::select("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$databasename."'"));
        $tablesvirtual  = [];

        //We will consider a empty database as invalid
        if ($tablesraw->count() == 0) {
            return new VirtualDatabase($databasename, [], false);
        }

        //Fetch all tables
        for ($i = 0; $i < $tablesraw->count(); $i++) {
            $tablesvirtual[] = VirtualTable::fromDatabase($databasename, $tablesraw[$i]->TABLE_NAME);
        }

        return new VirtualDatabase($databasename, $tablesvirtual);
    }

    //-------------------------------------------------
    // Export methods
    //-------------------------------------------------

    public function toJson ($options = 0) {
        return json_encode($this->toArray, $options);
    }

    public function toQuery () {
        //Prepare columns
        $queryTables = [];

        for ($i = 0; $i < count($this->tables); $i++) {
            $queryTables[] = $this->tables[$i]->toQuery();
        }

        //Prepare general statement
        $raw = implode(" ", $queryTables);

        //Sanitize
        $raw = preg_replace("/\s+/", " ", $raw);
        $raw = trim($raw);

        return $raw;
    }

    public function toLateQuery () {

    }

    public function toArray () {
        $tables_as_arrays = [];

        for ($i = 0; $i < count($this->tables); $i++) {
            $tables_as_arrays[] = $this->tables[$i]->toArray();
        }

        //All data response
        return [
            "tables"        => $tables_as_arrays,
            "databasename"  => $this->databasename,
            "attributes"    => $this->attributes,
        ];
    }
}
