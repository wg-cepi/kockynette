<?php

namespace App\CommonModule\Presenters;


use App\Components\Form\ContactForm;
use App\Components\Traits\Depozitum\TDepozitumDefault;
use App\Components\Traits\Depozitum\TDepozitumList;
use App\Model\Table\Address;
use App\Model\Table\Depozitum;
use Cepi\StringUtils;
use Nette;


class DepozitumPresenter extends BasePresenter
{
    /** @var  Depozitum */
    private $depo;

    public function actionDefault($id)
    {
        /** @var \App\Model\Service\Depozitum $depService */
        $depService = $this->context->getService('Depozitum');
        $depozitum = $depService->getById($id);

        if($depozitum === false) {
            throw new \Nette\Application\BadRequestException('Depozitum does not exist', 404);
        } else {
            $this->template->depo = $depozitum;
            $this->template->cats = $depService->getCats($depozitum);
            $this->template->depoXcontact = $depozitum->related('depozitum_x_contact');
        }
    }

    public function actionList()
    {
        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');

        /** @var \App\Model\Service\Depozitum $depoService */
        $depoService = $this->context->getService('Depozitum');

        $collection = new \App\Model\Collection\Depozitum($catService, $depoService);
        $collection->setData($depoService->getAll());
        $collection->withCats();
        $this->template->collection = $collection;

    }
}
