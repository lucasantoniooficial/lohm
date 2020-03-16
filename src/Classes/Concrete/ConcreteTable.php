<?php

namespace Aposoftworks\LOHM\Classes\Concrete;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualTable;

class ConcreteTable extends VirtualTable {

    //-------------------------------------------------
    // Column types
    //-------------------------------------------------

    public function string ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.string_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "varchar", "length" => $length]);
        $this->columns[]    = $column;
        return $column;
    }

    public function text ($name, $length = null) {
        $column             = new ConcreteColumn($name, ["type" => "text", "length" => $length]);
        $this->columns[]    = $column;
        return $column;
    }

    public function integer ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.integer_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "integer", "length" => $length]);
        $this->columns[]    = $column;
        return $column;
    }

    public function binary ($name, $length = null) {
        $length             = is_null($length) ? config("lohm.default_database.binary_size"):$length;
        $column             = new ConcreteColumn($name, ["type" => "binary", "length" => $length]);
        $this->columns[]    = $column;
        return $column;
    }

    public function boolean ($name) {
        $column             = new ConcreteColumn($name, ["type" => "integer", "length" => 1]);
        $this->columns[]    = $column;
        return $column;
    }

    public function timestamp ($name) {
        $column             = new ConcreteColumn($name, ["type" => "timestamp"]);
        $this->columns[]    = $column;
        return $column;
    }

    //-------------------------------------------------
    // Column helpers
    //-------------------------------------------------

    //-------------------------------------------------
    // Column collections
    //-------------------------------------------------

    public function timestamps ($createname = "date_created", $updatename = "date_updated", $deletename = "date_deleted") {
        $this->timestamp($createname);
        $this->timestamp($updatename)->nullable();
        $this->timestamp($deletename)->nullable();
    }

    public function userstamps ($createname = "id_user_created", $updatename = "id_user_updated", $deletename = "id_user_deleted") {

    }
}
