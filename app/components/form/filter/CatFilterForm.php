<?php
namespace App\Components\Form\Filter;

use App\Components\Form\AbstractForm;

class CatFilterForm extends AbstractForm
{
    public function create()
    {
        $this->addText('name', 'Jméno');

        $this->addRadioList('gender', 'Pohlaví', [
            'male' => 'kocour',
            'female' => 'kočka',
            'dc' => 'Nezáleží'
        ])
            ->setRequired('Prosím zvolte pohlaví');

        $this->addRadioList('castrated', 'Kastrovaná', [
            1 => 'Ano',
            0 => 'Ne',
            'dc' => 'Nezáleží'
        ])
            ->setRequired();

        $this->addRadioList('handicapped', 'Handicapovaná', [
            1 => 'Ano',
            0 => 'Ne',
            'dc' => 'Nezáleží'
        ])
            ->setRequired();
    }
}