<?php
namespace App\Components\Form;

class LoginForm extends AbstractForm
{
    public function create()
    {
        $this->addText('email', 'Email')
            ->setRequired('Prosím vyplňte svůj email.');

        $this->addPassword('password', 'Heslo')
            ->setRequired('Prosím vyplňte své heslo.');
    }
}