<?php
namespace App\Components\Form;


abstract class AbstractForm extends \Nette\Application\UI\Form
{
    public function __construct($parent = NULL, $name = NULL)
    {
        parent::__construct($parent, $name);

        $this->create();
    }

    abstract public function create();
}