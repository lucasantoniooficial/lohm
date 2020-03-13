<?php

use Aposoftworks\LOHM\Classes\Concrete\ConcreteTable as Table;
use Aposoftworks\LOHM\Classes\Concrete\ConcreteColumn as Column;
use Aposoftworks\LOHM\Classes\Concrete\ConcreteDatabase as Database;

class {{ $classname }} {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Database::create('{{ $tablename }}', function (Table $table) {
            $table->id();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Database::dropIfExists('{{ $tablename }}');
    }
}
