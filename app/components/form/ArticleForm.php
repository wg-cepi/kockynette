<?php
namespace App\Components\Form;

use Nette\Forms\Form;

class ArticleForm extends AbstractForm
{
    public function create()
    {
        $this->addText('headline', 'Nadpis')
            ->setRequired('Článek musí mít titulek');

        $this->addTextArea('content', 'Obsah')
            ->setRequired('Článek musí mít nějaký obsah');

        $this->addCheckbox('publish', 'Publikovat')
            ->setDefaultValue(1);
    }
}