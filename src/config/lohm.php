<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Migrations location
    |--------------------------------------------------------------------------
    |
    | You can change the place where LOHM tables will be stored, by default
    | we place them in the migrations folder
    |
    */

    "default_table_directory" => base_path()."/database/migrations/",

    /*
    |--------------------------------------------------------------------------
    | Cache type
    |--------------------------------------------------------------------------
    |
    | When creating our virtual database for keeping things balanced, we create
    | a cache that helps us fasten the process.
    |
    | values: none, json-cache, json-migration, database
    |
    */

    "cache_type" => "database",

    /*
    |--------------------------------------------------------------------------
    | Migration table name
    |--------------------------------------------------------------------------
    |
    | If you would like to add some other info to the name, you can add it here.
    | Version will only be added if the table_type config is set to versionify.
    | Uppername is the name after being sanitized by
    |
    | timestamp     : {timestamp}
    | name          : {name}
    | studlyname    : {studly}
    | camelname     : {camel}
    |
    */

    "default_table_namestructure" => "Model_{studly}",

    /*
    |--------------------------------------------------------------------------
    | Default database values
    |--------------------------------------------------------------------------
    |
    | Default values if you don't specify the length, beware that you need
    | to respect your database limit!
    |
    */

    "default_database" => [
        "string_size"   => 199,
        "integer_size"  => 255,
        "binary_size"   => 255,
    ]
];
