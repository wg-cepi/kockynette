<?php

namespace App\ModeratorModule\Presenters;


use Nette;
use App\Model\Authorizator;

abstract class BasePresenter extends \App\CommonModule\Presenters\BasePresenter
{
    public function startup()
    {
        parent::startup();

        //!(logged && (admin || moderator)) -> !logged || (!admin && !moderator)
        if(!$this->getUser()->isLoggedIn() || (!$this->getUser()->isInRole('moderator') && !$this->getUser()->isInRole('admin'))) {
            $this->redirect(':Common:Homepage:default');
        }
    }

}
