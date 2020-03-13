<?php

namespace Aposoftworks\LOHM\Contracts;

interface FromCreateCommand {
    /**
     * Returns a raw DB string that can be used as a query string
     *
     * @return string
     */
    public static function create ($arguments = [], $options = []);
}
