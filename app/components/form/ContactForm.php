<?php
namespace App\Components\Form;

use Nette\Forms\Form;

class ContactForm extends AbstractForm
{
    public function create()
    {
        $this->addText('firstname', 'Jméno')
            ->setRequired('Prosím vyplňte jméno');

        $this->addText('lastname', 'Příjmení')
            ->setRequired('Prosím vyplňte příjmení');

        $this->addText('email', 'Email')
            ->setType('email')
            ->addRule(Form::EMAIL, 'Zadaná emailová adresa není platná');

        $this->addText('phone', 'Telefon')
            ->setType('tel')
            ->addRule(Form::PATTERN, 'Zadejte telefon je formátu 123456789 nebo 123 456 789', '[0-9]{3}\s?[0-9]{3}\s?[0-9]{3}');
    }
}