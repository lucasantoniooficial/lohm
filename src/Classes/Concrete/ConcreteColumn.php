<?php

namespace Aposoftworks\LOHM\Classes\Concrete;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;

class ConcreteColumn extends VirtualColumn {

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
    }
}
