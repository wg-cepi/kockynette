<?php
namespace App\Components\Signal;

abstract class AbstractSignal
{
    const OK = 1;
    const FAIL = 0;

    protected $verbose = true;


    public function validate()
    {
        return self::OK;
    }

    public function perform()
    {
        return self::OK;
    }

    public function execute()
    {
        $res = $this->validate();
        if($res === self::OK) {
            $res = $this->perform();
        }

        return $res;
    }

    public function setVerbose($value)
    {
        $this->verbose = $value;
    }

}