<?php

namespace Aposoftworks\LOHM\Classes\Helpers;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class DirectoryHelper {
    public static function listFiles ($path) {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        $files = array();
        foreach ($rii as $file)
            if (!$file->isDir())
                $files[] = $file->getPathname();

        return $files;
    }
}
