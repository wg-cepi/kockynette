<?php
namespace App\ModeratorModule\Signals;

use App\Components\Signal\AbstractFormSignal;
use Cepi\DateUtils;
use Cepi\StringUtils;

class EditArticleSignal extends AbstractFormSignal
{
    public function perform()
    {
        $result = self::OK;

        $values = $this->form->getValues();
        $parameters = [
            'headline' => StringUtils::tmws($values->headline),
            'teaser' => $values->teaser,
            'content' => $values->content,
            'state' => $values->state,
            'edited' => DateUtils::Ymd()
        ];

        // !published -> published
        if ($this->presenter->article->published === null && $values->state == 'published') {
            $parameters['published'] = DateUtils::Ymd();
        }

        $this->presenter->article->update($parameters);

        if ($this->verbose) {
            if ($result) {
                $this->presenter->flashMessage('Článek upraven', 'success');
            } else {
                $this->presenter->flashMessage('Něco se pokazilo, zkuste opakovat akci', 'warning');
            }
        }

        return $result;
    }

}