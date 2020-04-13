<?php

namespace Aposoftworks\LOHM\Classes\Concrete;

//Traits
use Illuminate\Support\Traits\Macroable;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;

class ConcreteColumn extends VirtualColumn {

    use Macroable;

    //-------------------------------------------------
    // General types
    //-------------------------------------------------

    public function length ($newlength) {
        $this->attributes->length = $newlength;

        //Always return self for concatenation
        return $this;
    }

    public function default ($newdefault = null) {
        $this->attributes->default = $newdefault;

        //Always return self for concatenation
        return $this;
    }

    public function nullable ($bool = true) {
        $this->attributes->nullable = $bool ? "NULL":"NOT NULL";

        //Always return self for concatenation
        return $this;
    }

    public function unique () {
        $this->attributes->key = "UNI";

        //Always return self for concatenation
        return $this;
    }

    public function primary () {
        $this->attributes->key = "PRI";

        //Always return self for concatenation
        return $this;
    }

    public function unsigned () {

    }

    //-------------------------------------------------
    // Foreign types
    //-------------------------------------------------

    public function foreign () {
        if (!isset($this->attributes->foreign)) {
            $this->attributes->foreign          = [];
            $this->attributes->foreign["id"]    = "id";
            $this->attributes->foreign["table"] = $this->tablename;
        }

        //Always return self for concatenation
        return $this;
    }

    public function references ($idOfOtherTable) {
        if (!isset($this->attributes->foreign)) {
            $this->attributes->foreign          = [];
            $this->attributes->foreign["table"] = $this->tablename;
        }

        $this->attributes->foreign["id"]    = $idOfOtherTable;

        //Always return self for concatenation
        return $this;
    }

    public function on ($otherTable, $connection = null) {
        if (is_null($connection)) {
            $connection = config("database.default");
        }

        if (!isset($this->attributes->foreign)) {
            $this->attributes->foreign          = [];
            $this->attributes->foreign["id"]    = "id";
        }

        $this->attributes->foreign["table"]         = $otherTable;
        $this->attributes->foreign["connection"]    = $connection;

        //Always return self for concatenation
        return $this;
    }

    public function onDelete($method) {
        if (!isset($this->attributes->foreign)) {
            $this->attributes->foreign          = [];
            $this->attributes->foreign["id"]    = "id";
            $this->attributes->foreign["table"] = $this->tablename;
        }

        $this->attributes->foreign["method"] = " ON DELETE ".$method;

        //Always return self for concatenation
        return $this;
    }

    public function onUpdate($method) {
        if (!isset($this->attributes->foreign)) {
            $this->attributes->foreign          = [];
            $this->attributes->foreign["id"]    = "id";
            $this->attributes->foreign["table"] = $this->tablename;
        }

        $this->attributes->foreign["method"] = " ON UPDATE ".$method;

        //Always return self for concatenation
        return $this;
    }
}
