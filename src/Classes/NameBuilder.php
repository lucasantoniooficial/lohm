<?php

namespace Aposoftworks\LOHM\Classes;

//Helpers
use Carbon\Carbon;
use Illuminate\Support\Str;

class NameBuilder {
    public static function build ($namestring) {
        $raw    = $namestring;
        $format = config("lohm.default_table_namestructure");

        //Helpers
        $timestamp  = Carbon::now();
        $basicname  = $raw;
        $studlyname = Str::studly($raw);
        $camelname  = Str::camel($raw);
        $version    = NameBuilder::formatversion();

        //Exchange timestamp
        if (preg_match("/{timestamp}/", $format)) {
            $format = preg_replace("/{timestamp}/", $timestamp, $format);
        }
        //Exchange basicname
        if (preg_match("/{name}/", $format)) {
            $format = preg_replace("/{name}/", $basicname, $format);
        }
        //Exchange studlyname
        if (preg_match("/{studly}/", $format)) {
            $format = preg_replace("/{studly}/", $studlyname, $format);
        }
        //Exchange camelname
        if (preg_match("/{camel}/", $format)) {
            $format = preg_replace("/{camel}/", $camelname, $format);
        }
        //Exchange version
        if (preg_match("/{version}/", $format)) {
            $format = preg_replace("/{version}/", $version, $format);
        }

        return $format;
    }

    public static function formatversion () {
        $version = config("lohm.initial_version");

        return preg_replace("/\./", "_", $version);
    }
}
