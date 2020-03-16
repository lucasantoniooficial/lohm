<?php

namespace Aposoftworks\LOHM\Classes\Helpers;

class StubBuilder {

    public static function build ($srcstring, $data) {
        $result = $srcstring;

        //Get all variable fields
        preg_match_all("/{{.+}}/m", $srcstring, $requiredfields);
        $requiredfields = $requiredfields[0];

        //Replace them
        for ($i = 0; $i < count($requiredfields); $i++) {
            //Remove trailings
            $variable = preg_replace("/({{|}})/m", "", $requiredfields[$i]);
            //Trim
            $variable = trim($variable);
            //Remove variable identifier
            $variable = preg_replace("/^./", "", $variable);

            if (isset($data[$variable])) {
                $result = preg_replace('/{{\s*\$'.$variable.'\s*}}/m', $data[$variable], $result);
            }
        }

        return $result;
    }
}
