<?php

namespace App\AdminModule\Presenters;


use Nette;
use App\Model\Authorizator;

abstract class BasePresenter extends \App\CommonModule\Presenters\BasePresenter
{
    /*
    public function checkRequirements($element)
    {
        parent::checkRequirements($element);

        if (!$this->user->isLoggedIn()) {
            if ($this->user->logoutReason === Nette\Security\IUserStorage::INACTIVITY) {
                $this->flashMessage('Byli jste odhlášeni z důvodu nečinnosti. Přihlaste se prosím znovu', 'danger');
            } else {
                $this->flashMessage('Pro vstup do této sekce se musíte přihlásit', 'danger');
            }
            $this->redirect(':Common:Login:default', ['backlink' => $this->storeRequest()]);
        } elseif (!$this->user->isAllowed($this->name, Authorizator::READ)) {
            $this->flashMessage('Přístup byl odepřen. Nemáte oprávnění k zobrazení této stránky.', 'danger');
            $this->redirect(':Common:Login:default', ['backlink' => $this->storeRequest()]);
        }
    }
    */

    public function startup()
    {
        parent::startup();

        if(!$this->getUser()->isLoggedIn() || !$this->getUser()->isInRole('admin')) {
            $this->redirect(':Common:Homepage:default');
        }
    }

}
