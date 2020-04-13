<?php

namespace Aposoftworks\LOHM\Classes\Helpers;

//Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BuildCache {

    public static $cache;

    public static function initialize () {
        $method = config("lohm.cache_type");

        //Get
        switch ($method) {
            case "json-migration":
                if (!file_exists(base_path()."/database/migrations/config.json")) {
                    file_put_contents(base_path()."/database/migrations/config.json", json_encode(BuildCache::defaultCache(), JSON_PRETTY_PRINT));
                }

                $cache = json_decode(file_get_contents(base_path()."/database/migrations/config.json"));
            break;
            case "json-cache":
                $cache = json_decode(Cache::get("lohm.cache", BuildCache::defaultCache()));
            break;
            case "database":
                //Check if table exists
                $exists = DB::select(QueryHelper::checkTable("migrations"));

                return;

                //Create table
                if (count($exists) == 0) {
                    DB::statement("CREATE TABLE migrations (name varchar(100) PRIMARY KEY, current integer(1) NULL);");
                    DB::table('migrations')->insert(["current" => true, "name" => config("lohm.initial_version")]);
                }

                //Get data from database
                $cache = DB::select('SELECT * FROM migrations');
                $cache = BuildCache::fromDatabase($cache);
            break;
        }

        BuildCache::$cache = $cache;
    }

    public static function finalize () {
        $method = config("lohm.cache_type");

        //Set
        switch ($method) {
            case "json-migration":
                file_put_contents(base_path()."/database/migrations/config.json", json_encode(BuildCache::$cache, JSON_PRETTY_PRINT));
            break;
            case "json-cache":
                Cache::put("lohm.cache", json_encode(BuildCache::$cache));
            break;
            case "database":
                $cache = DB::select('SELECT * FROM migrations');
                $cache = BuildCache::fromDatabase($cache);
            break;
        }
    }

    public static function fromDatabase ($data) {
        $current    = "";
        $versions   = [];

        for ($i = 0; $i < count($data); $i++) {
            $versions[] = $data[$i]->name;

            //Current version installed
            if ($data[$i]->current) $current = $data[$i]->name;
        }

        return ["current" => $current, "versions" => $versions];
    }

    public static function defaultCache () {
        $initialversion = config("lohm.initial_version");
        return "{'version':'".$initialversion."','versions':['".$initialversion."']}";
    }

    public static function checkVersion () {
        return Cache::get("lohm.version", config("lohm.initial_version", "0.1.0"));
    }
}
