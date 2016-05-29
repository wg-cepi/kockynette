<?php

namespace App\CommonModule\Presenters;

use Nette;


class LoginPresenter extends BasePresenter
{
    public function actionDefault()
    {
        if($this->getUser()->isLoggedIn()) {
            $this->redirect(':Common:Homepage:default');
        }
    }

    public function createComponentSignInForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('email', 'Email')
            ->setRequired('Prosím vyplňte svůj email.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Prosím vyplňte své heslo.');

        //$form->addCheckbox('remember', 'Zůstat přihlášen');

        $form->addSubmit('submit', 'Přihlásit');

        $form->onSuccess[] = array($this, 'signInFormSucceeded');
        return $form;

    }

    public function signInFormSucceeded(Nette\Forms\Form $form)
    {
        $values = $form->values;

        try {
            $this->getUser()->login($values->email, $values->password);
            $this->redirect(':Common:Homepage:default');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávný email nebo heslo.');
        }
    }
}
