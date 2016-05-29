<?php
namespace App\Components\Control;
use Nette\Application\UI;

abstract class AbstractControl extends UI\Control
{
    protected function getTemplateName()
    {
        $reflection = new \ReflectionClass($this);
        return $reflection->getShortName() . '.latte';
    }
}