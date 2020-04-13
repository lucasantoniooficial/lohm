<?php

namespace Aposoftworks\LOHM\Classes\Virtual;

//Interfaces
use Illuminate\Contracts\Support\Jsonable;
use Aposoftworks\LOHM\Contracts\ToRawQuery;
use Illuminate\Contracts\Support\Arrayable;
use Aposoftworks\LOHM\Contracts\ComparableVirtual;

//Facades
use Illuminate\Support\Facades\DB;

class VirtualTable implements ToRawQuery, ComparableVirtual, Jsonable, Arrayable {

    /**
     * In case you created this from a database source, checks if the table exists and is valid
     *
     * @var boolean
     */
    protected $isvalid = true;

    /**
     * The real name of the database that this column belongs to
     *
     * @var string
     */
    protected $databasename;

    /**
     * The real name of this table
     *
     * @var string
     */
    protected $tablename;

    /**
     * The columns that compose this table
     *
     * @var \Aposoftworks\LOHM\Classes\Virtual\VirtualColumn array
     */
    protected $_columns;

    //-------------------------------------------------
    // Data methods
    //-------------------------------------------------

    public function name () {
        return $this->tablename;
    }

    public function columns () {
        return $this->_columns;
    }

    public function dataColumns () {
        $response = [];

        for ($i = 0; $i < count($this->_columns); $i++) {
            $column = $this->_columns[$i];

            $response[$column->name()] = [
                "order"     => $i,
                "column"    => $column,
            ];
        }

        return $response;
    }

    //-------------------------------------------------
    // Default methods
    //-------------------------------------------------

    public function __construct ($tablename, $columns = [], $databasename = "", $valid = true) {
        $this->databasename = $databasename;
        $this->tablename    = $tablename;
        $this->_columns     = $columns;
        $this->isvalid      = $valid;
    }

    public function isValid () {
        return $this->isvalid;
    }

    //-------------------------------------------------
    // Import methods
    //-------------------------------------------------

    public static function fromDatabase ($databasename, $tablename) {
        //Get the columns
        $allcolumns     = [];
        $virtualcolumns = [];

        //Check if the table exists
        try {
            $allcolumns     = collect(DB::select("SHOW COLUMNS FROM ".$databasename.".".$tablename));
        }
        catch (\Exception $e) {
            return new VirtualTable($tablename, $virtualcolumns, $databasename, false);
        }

        //Loop all columns
        for ($i = 0; $i < $allcolumns->count(); $i++) {
            $columnname = $allcolumns[$i]->Field;
            $columnattr = $allcolumns[$i];

            //Remove the name
            unset($columnattr->Field);

            $sanitizedattri = VirtualColumn::sanitize($columnattr);

            $virtualcolumns[] = new VirtualColumn($columnname, $sanitizedattri, $databasename, $tablename);
        }

        return new VirtualTable($tablename, $virtualcolumns, $databasename);
    }

    public static function fromMigration ($migrationpath) {

    }

    //-------------------------------------------------
    // Export methods
    //-------------------------------------------------

    public function toJson ($options = 0) {
        return json_encode($this->toArray(), $options);
    }

    public function toQuery () {
        //Prepare columns
        $queryColumns = [];

        for ($i = 0; $i < count($this->columns); $i++) {
            $queryColumns[] = $this->columns[$i]->toQuery();
        }

        //Prepare general statement
        $raw  = "CREATE TABLE ".$this->tablename." ( ";
        $raw .= implode(", ", $queryColumns);
        $raw .= " );";

        //Sanitize
        $raw = preg_replace("/\s+/", " ", $raw);
        $raw = trim($raw);

        return $raw;
    }

    public function toLateQuery () {
        //Prepare columns
        $queryColumns = [];

        for ($i = 0; $i < count($this->columns); $i++) {
            $query = $this->columns[$i]->toLateQuery();

            for ($x = 0; $x < count($query);$x++) {
                if ($query[$x] !== "") $queryColumns[] = $query[$x];
            }
        }

        return $queryColumns;
    }

    public function toArray() {
        //Get all columns as array
        $columns_as_arrays = [];

        for ($i = 0; $i < count($this->columns); $i++) {
            $columns_as_arrays[] = $this->columns[$i]->toArray();
        }

        //All data response
        return [
            "columns"       => $columns_as_arrays,
            "tablename"     => $this->tablename,
            "attributes"    => $this->attributes,
        ];
    }
}
