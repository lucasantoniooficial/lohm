<?php

namespace Aposoftworks\LOHM\Contracts;

interface ToRawQuery {
    /**
     * Returns a raw DB string that can be used as a query string
     *
     * @return string
     */
    public function toQuery ();

    /**
     * Returns a raw DB string that can be used as a query string
     *
     * @return string
     */
    public function toLateQuery ();
}
