<?php

namespace Aposoftworks\LOHM\Classes;

//Interfaces
use Aposoftworks\LOHM\Contracts\FromCreateCommand;

class CreateNewTable implements FromCreateCommand {
    public static function create ($arguments = [], $options = []) {
        $asdir      = config("lohm.table_type") === "versionify";
        $name       = class_basename($arguments["name"]);
        $tablepath  = preg_replace("/".$name."/", "", $arguments["name"]);
        $path       = base_path()."/database/migrations/".$tablepath;
        $filename   = NameBuilder::build($name);

        //Create stub
        $stub = new StubHelper(file_get_contents(__DIR__."/../Stubs/table.stub.php"), [
            "classname" => $filename,
            "tablename" => $name,
        ]);

        //Create path
        if (!is_dir($path)) {
            mkdir($path);
        }

        //Place stub inside migration
        if ($asdir) {
            //Create model dir
            if (!is_dir($path."/".$name)) {
                mkdir($path."/".$name);
            }

            //Place version
            if (file_exists($path."/".$name."/".$filename.".php")) {
                return false;
            }
            else {
                file_put_contents($path."/".$name."/".$filename.".php", $stub->parse());
            }
        }
        else {
            if (file_exists($path.$filename.".php")) {
                return false;
            }
            else {
                file_put_contents($path.$filename.".php", $stub->parse());
            }
        }

        //File created
        return true;
    }
}
