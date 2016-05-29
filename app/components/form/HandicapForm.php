<?php
namespace App\Components\Form;

class HandicapForm extends AbstractForm
{
    public function create()
    {
        $this->addText('name', 'Název')
            ->setRequired('Prosím vyplňte název handicapu');

        $this->addText('severity', 'Závažnost')
            ->setType('number')
            ->addRule(\Nette\Forms\Form::RANGE, 'Závažnost je v rozsahu 1 až 10', [1,10])
            ->setDefaultValue(1)
            ->setRequired('Prosím vyplňte závažnost handicapu');
    }
}