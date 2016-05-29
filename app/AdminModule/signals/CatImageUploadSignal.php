<?php
namespace App\AdminModule\Signals;

use App\Components\Signal\AbstractFormSignal;
use Cepi\DateUtils;

class CatImageUploadSignal extends AbstractFormSignal
{
    public function perform()
    {
        $result = self::OK;
        $cat = $this->presenter->cat;
        $values = $this->form->getValues();

        /** @var \App\Model\Service\Image $imageService */
        $imageService = $this->presenter->context->getService('Image');
        $catImages = [];
        /** @var \Nette\Http\FileUpload $image */
        foreach ($values->images as $image) {
            if ($image->isImage() && $image->isOk()) {
                $hash = sha1($image->sanitizedName . $image->size . time());

                $imageDirRoot = $this->presenter->context->getParameters()['imageDir'];
                $catDir = 'cat' . $cat->id;
                $location = $imageDirRoot . '/' . $catDir . '/' . $hash;

                $image->move($location);
                $smallImage = $image->toImage();
                $smallImage->resize(320, 180, \Nette\Utils\Image::SHRINK_ONLY | \Nette\Utils\Image::STRETCH);
                $smallImage->save($location . '_small.png');

                $catImages[] = $imageService->insert([
                    'name' => $image->sanitizedName,
                    'size' => $image->size,
                    'dir' => $catDir,
                    'hash' => $hash,
                    'type' => $image->contentType,
                    'created' => DateUtils::now()
                ])->id;

                $result &= self::OK;
            } else {
                $result &= self::FAIL;
            }
        }

        /** @var \App\Model\Service\Cat $catService */
        $catService =$this->presenter->context->getService('Cat');
        $catService->addImages($cat, $catImages);

        if($this->verbose) {
            if($result) {
                $this->presenter->flashMessage('Obrázky nahrány', 'info');
            } else {
                $this->presenter->flashMessage('Něco se pokazilo, zkuste opakovat akci', 'warning');
            }
        }

        return $result;
    }

}