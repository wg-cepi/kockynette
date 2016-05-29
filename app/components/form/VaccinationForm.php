<?php
namespace App\Components\Form;

class VaccinationForm extends AbstractForm
{
    public function create()
    {
        $this->addText('name', 'Název')
            ->setRequired('Prosím vyplňte název očkování');

        $this->addText('severity', 'Důležitost')
            ->setType('number')
            ->addRule(\Nette\Forms\Form::RANGE, 'Důležitost je v rozsahu 1 až 10', [1,10])
            ->setDefaultValue(1)
            ->setRequired('Prosím vyplňte důležitos očkování');
    }
}