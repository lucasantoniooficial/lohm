<?php

namespace Aposoftworks\LOHM\Classes;

class StubHelper {
    protected $srcstring;
    protected $targetstring;
    protected $data;

    public function __construct($srcstring, $data = []) {
        $this->srcstring    = $srcstring;
        $this->data         = $data;
    }

    public function parse () {
        //Set initial state
        $this->targetstring = $this->srcstring;

        //Get all variable fields
        preg_match_all("/{{.+}}/m", $this->srcstring, $requiredfields);
        $requiredfields = $requiredfields[0];

        //Replace them
        for ($i = 0; $i < count($requiredfields); $i++) {
            //Remove trailings
            $variable = preg_replace("/({{|}})/m", "", $requiredfields[$i]);
            //Trim
            $variable = trim($variable);
            //Remove variable identifier
            $variable = preg_replace("/^./", "", $variable);

            if (isset($this->data[$variable])) {
                $this->targetstring = preg_replace('/{{\s*\$'.$variable.'\s*}}/m', $this->data[$variable], $this->targetstring);
            }
        }

        return $this->targetstring;
    }
}
