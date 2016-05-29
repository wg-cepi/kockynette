<?php

namespace App\CommonModule\Presenters;


use App\Model\Service\Article;
use Nette;


class ArticlePresenter extends BasePresenter
{
    public $article;

    /** @var Article $articleService */
    public $articleService;

    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub

        $this->articleService = $this->context->getService('Article');
    }

    public function actionList()
    {
        $this->template->published = $this->articleService->getPublished();
    }

}