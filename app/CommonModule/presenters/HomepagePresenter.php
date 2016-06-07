<?php

namespace App\CommonModule\Presenters;

use Nette;


class HomepagePresenter extends BasePresenter
{
    /** @var \App\Model\Service\Article $catService */
    public $articleService;

    /** @var \App\Model\Service\Cat $catService */
    public $catService;

    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub
        $this->catService = $this->context->getService('Cat');
        $this->articleService = $this->context->getService('Article');
    }

    public function actionDefault()
    {
        $this->template->cats = $this->catService->getNewest();
        $this->template->articles = $this->articleService->getPublishedNewest();
    }
}
