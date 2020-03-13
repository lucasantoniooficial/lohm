<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Starting version
    |--------------------------------------------------------------------------
    |
    | This is the initial version that will be added to the tables everytime
    | you add a new one. Don't worry, you don't have to follow any standards
    |
    */

    "initial_version" => "0.1.0",

    /*
    |--------------------------------------------------------------------------
    | Unification vs versioning
    |--------------------------------------------------------------------------
    |
    | When creating a new table, this will decide if this is a only version
    | migration or a versioned table. You can set this to unify if you don't
    | care in keeping multiple versions of the tables you create.
    |
    | values: unify | versionify
    |
    */

    "table_type" => "versionify",

    /*
    |--------------------------------------------------------------------------
    | Cache type
    |--------------------------------------------------------------------------
    |
    | When creating our virtual database for keeping things balanced, we create
    | a cache that helps us fasten the process.
    |
    | values: none, json, database
    |
    */

    "cache_type" => "json",

    /*
    |--------------------------------------------------------------------------
    | Virtual Database class
    |--------------------------------------------------------------------------
    |
    | This is the class responsible for converting database entries and
    | migrations into a virtual json version that can be converted to a query
    | or a json file. It must implement virtualConvertable.
    |
    */

    "virtualdb_class" => \Aposoftworks\LOHM\Classes\Virtual\VirtualDatabase::class,

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
    | version       : {version}
    |
    */

    "default_table_namestructure" => "Model{studly}{version}"
];