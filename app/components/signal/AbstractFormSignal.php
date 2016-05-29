<?php
namespace App\Components\Signal;

use Nette\Application\UI\Presenter;
use Nette\Forms\Form;

abstract class AbstractFormSignal extends AbstractSignal
{
    /** @var Form */
    protected $form;

    /** @var Presenter  */
    protected $presenter;

    public function __construct(Form $form, Presenter $presenter)
    {
        $this->form = $form;
        $this->presenter = $presenter;
    }

}