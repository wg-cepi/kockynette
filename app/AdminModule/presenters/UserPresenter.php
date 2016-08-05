<?php

namespace App\AdminModule\Presenters;


use App\Model\Service\User;

class UserPresenter extends BasePresenter
{
    /** @var User @inject*/
    public $userService;

    public $users = [];

    public function actionDefault()
    {

    }

    public function actionList()
    {
        $this->users = $this->userService->getAll();
        $this->users->order('email ASC');
    }

    public function renderList()
    {
        $this->template->users = $this->users;
    }
}