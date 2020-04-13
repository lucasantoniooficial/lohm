<?php

namespace Aposoftworks\LOHM\Classes\Concrete;

//Traits
use Illuminate\Support\Traits\Macroable;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;

class ConcreteTable extends VirtualTable {

    use Macroable;

    //-------------------------------------------------
    // Column types
    //-------------------------------------------------

    public function string ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.string_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "varchar", "length" => $length], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function text ($name, $length = null) {
        $column             = new ConcreteColumn($name, ["type" => "text", "length" => $length], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function enum ($name, $options) {
        $column             = new ConcreteColumn($name, ["type" => "enum", "length" => $options], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function integer ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.integer_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "integer", "length" => $length], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function binary ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.binary_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "binary", "length" => $length], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function boolean ($name) {
        $column             = new ConcreteColumn($name, ["type" => "integer", "length" => 1], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function timestamp ($name) {
        $column             = new ConcreteColumn($name, ["type" => "timestamp"], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    //-------------------------------------------------
    // Column helpers
    //-------------------------------------------------

    public function id ($name = "id") {
        $column             = new ConcreteColumn($name, ["type" => "integer", "extra" => "unsigned", "key" => "PRI"], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function sid ($name = "sid") {
        $column             = new ConcreteColumn($name, ["type" => "varchar", "length" => 11, "key" => "PRI"], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    public function uuid ($name = "uuid") {
        $column             = new ConcreteColumn($name, ["type" => "varchar", "length" => 36, "key" => "PRI"], "", $this->tablename);
        $this->_columns[]   = $column;
        return $column;
    }

    //-------------------------------------------------
    // Column collections
    //-------------------------------------------------

    public function timestamps ($createname = "date_created", $updatename = "date_updated", $deletename = "date_deleted") {
        $this->timestamp($createname);
        $this->timestamp($updatename)->nullable();
        $this->timestamp($deletename)->nullable();
    }
}
