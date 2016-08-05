<?php

namespace App\AdminModule\Presenters;


use Nette;
use App\Model\Authorizator;

abstract class BasePresenter extends \App\CommonModule\Presenters\BasePresenter
{
    public function startup()
    {
        parent::startup();

        if(!$this->getUser()->isLoggedIn() || !$this->getUser()->isInRole('admin')) {
            $this->flashMessage('Nemáte dostatečná práva nebo nejste přihlášený', 'error');
            $this->redirect(':Common:Homepage:default');
        }
    }

}
