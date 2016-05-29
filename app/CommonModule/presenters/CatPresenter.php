<?php

namespace App\CommonModule\Presenters;

use App\Model\Service\Cat;
use Nette;


class CatPresenter extends BasePresenter
{
    public $cat;

    /** @var Cat $catService */
    public $catService;

    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub

        $this->catService = $this->context->getService('Cat');
    }

    public function actionDefault($id)
    {
        $cat = $this->catService->getById($id);
        if ($cat === false) {
            throw new \Nette\Application\BadRequestException('Cat does not exist', 404);
        } else {
            $this->template->cat = $cat;
        }
    }

    public function actionList()
    {
        $section = $this->session->getSection('cat');
        if($section->filter) {
            $filter = $section->filter;
            $this->template->cats = $this->catService->filter($filter);
        } else {
            $this->template->cats = $this->catService->getAll();
        }

    }

    public function createComponentCatFilterForm()
    {
        /** @var \App\Model\Service\Color $colorService */
        $colorService = $this->context->getService('Color');

        /** @var \App\Model\Service\Depozitum $depoService */
        $depoService = $this->context->getService('Depozitum');

        $filter = $this->session->getSection('cat')->filter;

        $form = new Nette\Application\UI\Form();
        $form->addText('name', 'Jméno')
            ->setDefaultValue(isset($filter['name']) ? $filter['name'] : '');

        $form->addRadioList('gender', 'Pohlaví', [
            'male' => 'kocour',
            'female' => 'kočka',
            'dc' => 'Nezáleží'
        ])
            ->setDefaultValue(isset($filter['gender']) ? $filter['gender'] : 'dc')
            ->setRequired('Prosím zvolte pohlaví');

        $form->addRadioList('castrated', 'Kastrovaná', [
            1 => 'Ano',
            0 => 'Ne',
            'dc' => 'Nezáleží'
        ])
            ->setDefaultValue(isset($filter['castrated']) ? $filter['castrated'] : 'dc')
            ->setRequired();

        $form->addRadioList('handicapped', 'Handicapovaná', [
            1 => 'Ano',
            0 => 'Ne',
            'dc' => 'Nezáleží'
        ])
            ->setDefaultValue(isset($filter['handicapped']) ? $filter['handicapped'] : 'dc')
            ->setRequired();

        $multiselectColors = $form->addMultiSelect('colors', 'Barvy', $colorService->getAllAsIdNamePairs());
        if(!empty($filter['colors'])) {
            $multiselectColors->setDefaultValue($filter['colors']);
        }

        $multiselectDepo = $form->addMultiSelect('depos', 'Depozita', $depoService->getAllAsIdNamePairs());
        if(!empty($filter['depos'])) {
            $multiselectDepo->setDefaultValue($filter['depos']);
        }

        $form->addSubmit('submit', 'Filtrovat');
        $form->addSubmit('reset', 'Reset');

        $form->onSuccess[] = array($this, 'catFilterFormSuccess');

        return $form;
    }

    public function catFilterFormSuccess(Nette\Forms\Form $form)
    {
        $submitButton = $form->isSubmitted();
        if($submitButton->name == 'reset') {
            $section = $this->session->getSection('cat');
            $section->filter = null;
        } else {
            $section = $this->session->getSection('cat');
            $section->filter = $form->getValues(true);
        }
        $this->redirect('list');
    }
}