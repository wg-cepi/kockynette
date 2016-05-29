<?php

namespace App\CommonModule\Presenters;

use Nette;


class LogoutPresenter extends BasePresenter
{
    public function actionDefault()
    {
        $this->getUser()->logout();
        $this->redirect(':Common:Homepage:default');
    }

}
