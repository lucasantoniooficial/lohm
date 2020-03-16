<?php

namespace Aposoftworks\LOHM\Classes\Helpers;

//Facades
use Illuminate\Support\Facades\Cache;

class BuildCache {
    public static function checkCache () {

    }

    public static function checkVersion () {
        return Cache::get("lohm.version", config("lohm.initial_version", "0.1.0"));
    }
}
