<?php
namespace App\ModeratorModule\Signals;

use App\Components\Signal\AbstractFormSignal;
use Cepi\DateUtils;
use Cepi\StringUtils;

class AddArticleSignal extends AbstractFormSignal
{
    public function perform()
    {
        $result = self::OK;

        $values = $this->form->getValues();
        $parameters = [
            'headline' => StringUtils::tmws($values->headline),
            'content' => $values->content,
            'state' => $values->state,
            'user_id' => $this->presenter->getUser()->id,
            'created' => DateUtils::Ymd()
        ];

        if ($values->state == 'published') {
            $parameters['published'] = DateUtils::Ymd();
        }

        $this->presenter->articleService->insert($parameters);

        if ($this->verbose) {
            if ($result) {
                if ($values->state == 'waiting') {
                    $this->presenter->flashMessage('Článek přidán jako čekající na schválení', 'success');
                } else {
                    $this->presenter->flashMessage('Článek přidán', 'success');
                }
            } else {
                $this->presenter->flashMessage('Něco se pokazilo, zkuste opakovat akci', 'warning');
            }
        }

        return $result;
    }

}