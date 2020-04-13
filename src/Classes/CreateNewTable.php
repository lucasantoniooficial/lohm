<?php

namespace Aposoftworks\LOHM\Classes;

//Helpers
use Aposoftworks\LOHM\Classes\Helpers\NameBuilder;
use Aposoftworks\LOHM\Classes\Helpers\StubBuilder;

class CreateNewTable {
    public static function create ($arguments = [], $options = []) {
        $name       = class_basename($arguments["classname"]);
        $tablepath  = preg_replace("/".$name."/", "", $arguments["classname"]);
        $path       = config("lohm.default_table_directory").$tablepath;
        $filename   = NameBuilder::build($name);
        $stubpath   = is_file(resource_path("/stubs/lohm.php")) ? resource_path("/stubs/lohm.php"): __DIR__."/../Stubs/table.stub.php";

        //Create stub
        $stub = StubBuilder::build(file_get_contents($stubpath), [
            "classname" => $filename,
            "tablename" => key_exists("name", $arguments) ? $arguments["name"] : strtolower($name),
        ]);

        //Create path
        if (!is_dir($path)) {
            mkdir($path);
        }

        //Place stub inside migration
        if (file_exists($path.$filename.".php")) {
            return false;
        }
        else {
            file_put_contents($path.$filename.".php", $stub);
        }

        //File created
        return true;
    }
}
