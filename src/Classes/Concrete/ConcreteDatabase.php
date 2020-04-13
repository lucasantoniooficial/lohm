<?php

namespace Aposoftworks\LOHM\Classes\Concrete;

//Traits
use Illuminate\Support\Traits\Macroable;

//Classes
use Aposoftworks\LOHM\Classes\Virtual\VirtualColumn;

class ConcreteDatabase extends VirtualColumn {

    use Macroable;

    public static function table ($tablename, $callback) {

    }

    public static function dropTable ($tablename) {

    }
}
