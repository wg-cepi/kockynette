<?php

namespace App\AdminModule\Presenters;

use App\Model\Service\Address;

use Nette;


class AddressPresenter extends BasePresenter
{
    public $address;

    /** @var Address $colorService */
    public $addressService;

    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub
        $this->addressService = $this->context->getService('Address');
    }

    public function actionDefault($id)
    {
        $this->address = $this->addressService->getById($id);
        if($this->address !== false) {
            $this->template->adr = $this->address;
        } else {
            throw new Nette\Application\BadRequestException('Address does not exist', 404);
        }
    }

    public function actionList()
    {
        $this->template->addresses = $this->addressService->getAll();
    }

    public function actionEdit($id)
    {

    }

    public function createComponentEditColorForm()
    {

    }

    public function editColorFormSuccess(Nette\Forms\Form $form)
    {

    }

    public function actionAdd()
    {

    }

    public function createComponentAddColorForm()
    {

    }

    public function addColorFormSuccess(Nette\Forms\Form $form)
    {

    }
}
