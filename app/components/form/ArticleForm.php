<?php
namespace App\Components\Form;

class ArticleForm extends AbstractForm
{
    public function create()
    {
        $this->addText('headline', 'Nadpis')
            ->setRequired('Článek musí mít titulek');

        $this->addTextArea('content', 'Obsah')
            ->setRequired('Článek musí mít nějaký obsah');

        $this->addRadioList('state', 'Stav', [
            'published' => 'Publikovaný',
            'waiting' => 'Čekající na schválení'
        ])
            ->setDefaultValue('published')
            ->setRequired('Prosím zvolte stav');
    }
}