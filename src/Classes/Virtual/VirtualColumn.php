<?php

namespace Aposoftworks\LOHM\Classes\Virtual;

//Interfaces
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Aposoftworks\LOHM\Contracts\ToRawQuery;
use Aposoftworks\LOHM\Contracts\ComparableVirtual;

//Facades
use Illuminate\Support\Facades\DB;

class VirtualColumn implements ToRawQuery, ComparableVirtual, Jsonable, Arrayable {

    /**
     * The real name of the database that this column belongs to
     *
     * @var string
     */
    protected $databasename;

    /**
     * The real name of the database's table that this column belongs to
     *
     * @var string
     */
    protected $tablename;

    /**
     * The real name of the column
     *
     * @var string
     */
    protected $columnname;

    /**
     * Attributes that apply to this column
     *
     * @var array
     */
    protected $attributes;

    //-------------------------------------------------
    // Default methods
    //-------------------------------------------------

    public function __construct ($columnname, $attributes = [], $databasename = "", $tablename = "") {
        $this->databasename = $databasename;
        $this->tablename    = $tablename;
        $this->columnname   = $columnname;
        $this->attributes   = $attributes;
    }

    public function isValid () {
        return !is_null($this->attributes);
    }

    public function key () {
        switch ($this->attributes->Key) {
            case "PRI":
                return "PRIMARY KEY";
            case "UNI":
                return "";
            default:
                return "";
        }
    }

    //-------------------------------------------------
    // Import methods
    //-------------------------------------------------

    public static function fromDatabase ($databasename, $tablename, $columnname) {
        $allcolumns = collect(DB::select("SHOW COLUMNS FROM ".$databasename.".".$tablename));
        $column     = $allcolumns->filter(function ($value) use ($columnname) {
            return $value->Field === $columnname;
        })->first();

        //Unset the name since we already got that in a specific property
        unset($column->Field);

        return new VirtualColumn($columnname, $column, $databasename, $tablename);
    }

    //-------------------------------------------------
    // Export methods
    //-------------------------------------------------

    public function toJson ($options = 0) {
        return json_encode($this->toArray(), $options);
    }

    public function toQuery () {
        //Configuration
        $name       = $this->columnname;
        $increment  = $this->attributes->Extra === "auto_increment" ? "AUTO_INCREMENT":"";
        $type       = $this->attributes->Type;
        $nullable   = $this->attributes->Null === "NO" ? "NOT NULL":"";
        $primary    = $this->key();
        $default    = is_null($this->attributes->Default) ? "":"DEFAULT '".$this->attributes->Default."'";

        //Sanitization
        $raw = implode(" ", [$name, $type, $increment, $nullable, $default, $primary]);
        $raw = preg_replace("/\s+/", " ", $raw);
        $raw = trim($raw);

        return $raw;
    }

    public function toArray () {
        return ["columnname" => $this->columnname, "attributes" => $this->attributes];
    }
}
