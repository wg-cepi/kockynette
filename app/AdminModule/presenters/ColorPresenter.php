<?php

namespace App\AdminModule\Presenters;

use App\Model\Service\Color;
use Cepi\StringUtils;
use Nette;


class ColorPresenter extends BasePresenter
{
    private $color;

    public function actionDefault($id)
    {
        /** @var Color $colorService */
        $colorService = $this->context->getService('Color');
        $color = $colorService->getById($id);
        if($color !== false) {
            $this->color = $color;
            $this->template->color = $this->color;
            $this->template->cats = $colorService->getCats($color);
        } else {
            throw new Nette\Application\BadRequestException('Color does not exist', 404);
        }
    }

    public function actionList()
    {
        /** @var Color $colorService */
        $colorService = $this->context->getService('Color');

        $this->template->colors = $colorService->getAll();
    }

    public function actionEdit($id)
    {
        /** @var Color $colorService */
        $colorService = $this->context->getService('Color');
        $color = $colorService->getById($id);
        if($color !== false) {
           $this->color = $color;
        } else {
            throw new Nette\Application\BadRequestException('Color does not exist', 404);
        }
    }

    public function createComponentEditColorForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addText('name', 'Barva')
            ->setDefaultValue($this->color->name)
            ->setRequired('Prosím vyplňte barvu');

        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = array($this, 'editColorFormSuccess');

        return $form;
    }

    public function editColorFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();
        if($this->color) {
            $this->color->update([
                'name' => StringUtils::caws($values->name)
            ]);
            $this->flashMessage('Barva byla upravena', 'info');
        } else {
            $this->flashMessage('Barva neexistuje', 'warning');
        }

        $this->redirect('list');
    }

    public function actionAdd()
    {

    }

    public function createComponentAddColorForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addText('name', 'Barva')
            ->setRequired('Prosím vyplňte barvu');

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addColorFormSuccess');

        return $form;
    }

    public function addColorFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        /** @var Color $colorService */
        $colorService = $this->context->getService('Color');
        $colorService->insert([
            'name' => StringUtils::caws($values->name)
        ]);
        $this->flashMessage('Barva byla přidána', 'info');
        $this->redirect('list');
    }
}

