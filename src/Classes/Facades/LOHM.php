<?php
namespace Aposoftworks\LOHM\Classes\Facades;

use Illuminate\Support\Facades\Facade;

class LOHM extends Facade {
    protected static function getFacadeAccessor() {
        return \Aposoftworks\LOHM\Classes\LOHM::class;
    }
}
