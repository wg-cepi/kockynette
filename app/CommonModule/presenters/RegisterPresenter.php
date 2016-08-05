<?php

namespace App\CommonModule\Presenters;

use App\Model\Service\User;
use Cepi\DateUtils;
use Nette\Forms\Form;
use Nette;


class RegisterPresenter extends BasePresenter
{
    public function actionDefault()
    {

    }

    public function createComponentRegisterForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addText('email', 'E-mail: *', 35)
            ->setType('email')
            ->addRule(Nette\Forms\Form::FILLED, 'Vyplňte email prosím')
            ->addCondition(Form::FILLED)
            ->addRule(Form::EMAIL, 'Neplatná emailová adresa');

        $form->addPassword('password', 'Heslo: *', 20)
            ->setOption('description', 'Alespoň 6 znaků')
            ->addRule(Form::FILLED, 'Vyplňte heslo prosím')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků.', 6);

        $form->addPassword('password2', 'Heslo znovu: *', 20)
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::FILLED, 'Heslo znovu')
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);

        $form->addSubmit('submit', 'Registrovat');

        $form->onSuccess[] = array($this, 'registerFormSuccess');
        return $form;

    }

    public function registerFormSuccess(Form $form) {
        $values = $form->getValues();

        /** @var User $userService */
        $userService = $this->context->getService('User');
        $user = $userService->register([
            'email' => $values->email,
            'password' => password_hash($values->password, PASSWORD_BCRYPT),
            'role' => \App\Model\Table\User::ROLE_USER,
            'created' => DateUtils::Ymd()
        ]);
        if($user){
            $this->flashMessage('Registrace se zdařila');
            $this->redirect('Login:default');
        }
    }
}
