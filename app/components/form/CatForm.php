<?php
namespace App\Components\Form;

use Nette\Forms\Form;

class CatForm extends AbstractForm
{
    public function create()
    {
        $this->addText('name', 'Jméno')
            ->setRequired('Prosím vyplňte jméno kočky');

        $this->addText('born', 'Datum narození')
            ->setType('date')
            ->setRequired('Prosím vyplňte datum narození kočky');


        $this->addRadioList('gender', 'Pohlaví', [
            'male' => 'kocour',
            'female' => 'kočka'
        ])
            ->setDefaultValue('male')
            ->setRequired('Prosím zvolte pohlaví');

        $this->addRadioList('castrated', 'Kastrovaná', [
            1 => 'Ano',
            0 => 'Ne'
        ])
            ->setDefaultValue(0)
            ->setRequired();
    }
}