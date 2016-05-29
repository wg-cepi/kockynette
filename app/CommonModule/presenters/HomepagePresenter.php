<?php

namespace App\CommonModule\Presenters;

use Nette;


class HomepagePresenter extends BasePresenter
{
    public function actionDefault()
    {
        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        $this->template->cats = $catService->getNewest();
    }
}
