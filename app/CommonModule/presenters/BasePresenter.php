<?php

namespace App\CommonModule\Presenters;

use Nette;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public function beforeRender()
    {
        parent::beforeRender(); // TODO: Change the autogenerated stub
        $that = $this;
        $this->template->addFilter('catToImage', function (Nette\Database\Table\ActiveRow $row, $type = 'big') use ($that) {
            $imgDir = $that->context->getParameters()['imagesDirAbsolute'];
            $image = $row->related('cat_x_image')->limit(1)->fetch();
            if ($image !== false) {
                $image = $image->image;
                if ($type == 'big') {
                    return $imgDir . '/' . $image->dir . '/' . $image->hash;
                } else {
                    return $imgDir . '/' . $image->dir . '/' . $image->hash . '_' . $type . '.png';
                }

            } else {
                return $imgDir . '/placeholders/320x180.png';
            }

        });

        $this->template->addFilter('imageSrc', function (Nette\Database\Table\ActiveRow $image, $type = 'big') use ($that) {
            $imgDir = $that->context->getParameters()['imagesDirAbsolute'];
            if ($image !== false) {
                if ($type == 'big') {
                    return $imgDir . '/' . $image->dir . '/' . $image->hash;
                } else {
                    return $imgDir . '/' . $image->dir . '/' . $image->hash . '_' . $type . '.png';
                }
            } else {
                return $imgDir . '/placeholders/320x180.png';
            }

        });
    }


}
