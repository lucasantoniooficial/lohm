<?php

use \Aposoftworks\LOHM\Providers\LohmServiceProvider;

class TypesTest extends \Orchestra\Testbench\TestCase {

    // Use annotation @test so that PHPUnit knows about the test
    /** @test */
    public function visit_test_route () {

    }

    // When testing inside of a Laravel installation, this is not needed
    protected function getPackageProviders($app) {
        return [
            '\Aposoftworks\LOHM\Providers\LohmServiceProvider'
        ];
    }

    // When testing inside of a Laravel installation, this is not needed
    protected function setUp() {
        parent::setUp();
    }
}
