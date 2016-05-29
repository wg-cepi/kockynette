<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Signals\AddCatSignal;
use App\AdminModule\Signals\CatImageUploadSignal;
use App\Components\Form\CatForm;
use App\Model\Service\Cat;
use App\Model\Service\Image;
use Cepi\DateUtils;
use Cepi\StringUtils;
use Nette;


class CatPresenter extends BasePresenter
{
    public $cat;

    private $handicaps = [];

    private $vaccinations = [];

    private $colors = [];

    private $images = [];

    public function actionAdd()
    {

    }

    public function createComponentAddCatForm()
    {
        $form = new CatForm();

        /** @var \App\Model\Service\Color $colorService */
        $colorService = $this->context->getService('Color');
        $form->addMultiSelect('colors', 'Barva', $colorService->getAllAsIdNamePairs());

        /** @var \App\Model\Service\Depozitum $depoService */
        $depoService = $this->context->getService('Depozitum');
        $form->addSelect('depozitum', 'Depozitum', $depoService->getAllAsIdNamePairs())
            ->setPrompt('Vyberte depozitum');

        /** @var \App\Model\Service\Handicap $hdService */
        $hdService = $this->context->getService('Handicap');
        $form->addMultiSelect('handicaps', 'Handicapy', $hdService->getAllAsIdNamePairs());

        /** @var \App\Model\Service\Vaccination $vacService */
        $vacService = $this->context->getService('Vaccination');
        $form->addMultiSelect('vaccinations', 'Očkování', $vacService->getAllAsIdNamePairs());

        $form->addMultiUpload('images', 'Obrázky');

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addCatFormSuccess');
        return $form;
    }

    public function addCatFormSuccess(Nette\Forms\Form $form)
    {

        $signal = new AddCatSignal($form, $this);
        $signal->execute();

        $this->redirect(':Common:Cat:list');
    }

    public function actionEdit($id)
    {
        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        $cat = $catService->getById($id);

        if ($cat !== false) {
            $this->cat = $cat;
            $this->handicaps = $catService->getHandicaps($cat);
            $this->vaccinations = $catService->getVaccinations($cat);
            $this->colors = $catService->getColors($cat);
            $this->images = $catService->getImages($cat);

            $this->template->cat = $this->cat;
            $this->template->handicaps = $this->handicaps;
            $this->template->vaccinations = $this->vaccinations;
            $this->template->colors = $this->colors;
            $this->template->images = $this->images;

        } else {
            throw new Nette\Application\BadRequestException('Cat does not exist', 404);
        }
    }

    public function createComponentEditCatForm()
    {
        $form = new CatForm();
        $form->setDefaults([
            'name' => $this->cat->name,
            'born' => DateUtils::datePicker($this->cat->born),
            'gender' => $this->cat->gender,
            'castrated' => $this->cat->castrated
        ]);

        /** @var \App\Model\Service\Depozitum $depoService */
        $depoService = $this->context->getService('Depozitum');
        $form->addSelect('depozitum', 'Depozitum', $depoService->getAllAsIdNamePairs())
            ->setPrompt('Vyberte depozitum');

        if($this->cat->depozitum) {
            $form->setDefaults([
                'depozitum' => $this->cat->depozitum->id
            ]);
        }

        $form->onSuccess[] = array($this, 'editCatFormSuccess');

        $form->addSubmit('submit', 'Uložit');
        return $form;
    }

    public function editCatFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $this->cat->update([
            'name' => StringUtils::tmws($values->name),
            'born' => DateUtils::Ymd($values->born),
            'gender' => $values->gender,
            'castrated' => $values->castrated,
            'depozitum_id' => $values->depozitum
        ]);

        $this->flashMessage('Kočka byla upravena', 'info');
        $this->redirect('edit', ['id' => $this->cat->id]);
    }

    public function createComponentAddCatColorForm()
    {
        $form = new Nette\Application\UI\Form();

        /** @var \App\Model\Service\Color $colorService */
        $colorService = $this->context->getService('Color');

        $colors = [];
        if($this->colors) {
            $colorIds = [];
            foreach($this->colors as $color) {
                $colorIds[] = $color->id;
            }
            foreach($colorService->getDiff($colorIds) as $color) {
                $colors[$color->id] = $color->name;
            }
        } else {
            $colors = $colorService->getAllAsIdNamePairs();
        }

        $form->addMultiSelect('colors', 'Barvy', $colors);

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addCatColorFormSuccess');
        return $form;
    }

    public function addCatColorFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $allColors = $this->getHttpRequest()->getPost('colors', []);
        $newColors = array_diff($allColors, $values->colors);

        /** @var \App\Model\Service\Color $colorService */
        $colorService = $this->context->getService('Color');
        $catColors = $values->colors;
        foreach($newColors as $key => $name) {
            $catColors[] = $colorService->insert([
                'name' => StringUtils::tmws($name),
            ])->id;
        }

        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        try {
            $catService->addColors($this->cat, $catColors);
            $this->flashMessage('Barvy přidány');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Nelze přidat stejné barvy', 'warning');
        }

        $this->redirect('edit', ['id' => $this->cat->id]);
    }

    public function createComponentAddCatHandicapForm()
    {
        $form = new Nette\Application\UI\Form();

        /** @var \App\Model\Service\Handicap $handicapService */
        $handicapService = $this->context->getService('Handicap');

        $handicaps = [];
        if($this->handicaps) {
            $handicapIds = [];
            foreach($this->handicaps as $handicap) {
                $handicapIds[] = $handicap->id;
            }
            foreach($handicapService->getDiff($handicapIds) as $handicap) {
                $handicaps[$handicap->id] = $handicap->name;
            }
        } else {
            $handicaps = $handicapService->getAllAsIdNamePairs();
        }

        $form->addMultiSelect('handicaps', 'Handicapy', $handicaps);

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addCatHandicapFormSuccess');
        return $form;
    }


    public function addCatHandicapFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $allHandicaps = $this->getHttpRequest()->getPost('handicaps', []);
        $newHandicaps = array_diff($allHandicaps, $values->handicaps);

        /** @var \App\Model\Service\Handicap $handicapService */
        $handicapService = $this->context->getService('Handicap');
        $catHandicaps = $values->handicaps;
        foreach($newHandicaps as $key => $name) {

            $catHandicaps[] = $handicapService->insert([
                'name' => StringUtils::tmws($name),
            ])->id;
        }

        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        try {
            $catService->addHandicaps($this->cat, $catHandicaps);
            $this->flashMessage('Handicapy přidány');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Nelze přidat stejné handicapy', 'warning');
        }

        $this->redirect('edit', ['id' => $this->cat->id]);
    }


    public function createComponentAddCatVaccinationForm()
    {
        $form = new Nette\Application\UI\Form();

        /** @var \App\Model\Service\Vaccination $vaccinationService */
        $vaccinationService = $this->context->getService('Vaccination');

        $vaccinations = [];
        if($this->vaccinations) {
            $vaccinationsIds = [];
            foreach($this->vaccinations as $vaccination) {
                $vaccinationsIds[] = $vaccination->id;
            }
            foreach($vaccinationService->getDiff($vaccinationsIds) as $vaccination) {
                $vaccinations[$vaccination->id] = $vaccination->name;
            }
        } else {
            $vaccinations = $vaccinationService->getAllAsIdNamePairs();
        }

        $form->addMultiSelect('vaccinations', 'Očkování', $vaccinations);

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addCatVaccinationsFormSuccess');
        return $form;
    }


    public function addCatVaccinationsFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $allVaccinations = $this->getHttpRequest()->getPost('vaccinations', []);
        $newVaccinations = array_diff($allVaccinations, $values->vaccinations);

        /** @var \App\Model\Service\Vaccination $vaccinationService */
        $vaccinationService = $this->context->getService('Vaccination');
        $catVacciantions = $values->vaccinations;
        foreach($newVaccinations as $key => $name) {
            $catVacciantions[] = $vaccinationService->insert([
                'name' => StringUtils::tmws($name),
            ])->id;
        }

        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        try {
            $catService->addVaccinations($this->cat, $catVacciantions);
            $this->flashMessage('Očkování přidány');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Nelze přidat stejná očkování', 'warning');
        }

        $this->redirect('edit', ['id' => $this->cat->id]);
    }

    public function createComponentAddCatImageForm()
    {
        $form = new Nette\Application\UI\Form();
        $form->addMultiUpload('images', 'Obrázky')
            ->setRequired();

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addCatImageFormSuccess');

        return $form;
    }

    public function addCatImageFormSuccess(Nette\Forms\Form $form)
    {
        $signal = new CatImageUploadSignal($form, $this);
        $signal->execute();

        $this->redirect('edit', ['id' => $this->cat->id]);
    }

    public function actionDelete($id)
    {
        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        $cat = $catService->getById($id);
        if($cat !== false) {
           $this->cat = $cat;
        } else {
            throw new Nette\Application\BadRequestException('Cat does not exist', 404);
        }
    }

    /** @secured */
    public function handleDelete($id)
    {
        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');
        $catService->delete($this->cat);
        $this->flashMessage('Kočka smazána');

        $this->redirect(':Common:Cat:list');
    }

    public function handleDeleteImage($cid, $iid)
    {
        if($this->cat) {
            /** @var \App\Model\Service\Cat $catService */
            $catService = $this->context->getService('Cat');

            /** @var \App\Model\Service\Image $imageService */
            $imageService = $this->context->getService('Image');
            $image = $imageService->getById($iid);
            if($image !== false) {
                $catService->deleteImage($this->cat, $image);
                $this->flashMessage('Obrázek kočky byl smazán', 'info');
            } else {
                $this->flashMessage('Obrázek neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Kočka neexistuje', 'warning');
        }

        $this->redirect('edit', ['id' => $cid]);
    }

    /** @secured */
    public function handleDeleteHandicap($cid, $hid)
    {
        if($this->cat) {
            /** @var \App\Model\Service\Cat $catService */
            $catService = $this->context->getService('Cat');

            /** @var \App\Model\Service\Handicap $handicapService */
            $handicapService = $this->context->getService('Handicap');
            $handicap = $handicapService->getById($hid);
            if($handicap !== false) {
                $catService->deleteHandicap($this->cat, $handicap);
                $this->flashMessage('Handicap kočky byl smazán', 'info');
            } else {
                $this->flashMessage('Handicap neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Kočka neexistuje', 'warning');
        }

        $this->redirect('edit', ['id' => $cid]);
    }

    /** @secured */
    public function handleDeleteVaccination($cid, $vid)
    {
        if($this->cat) {
            /** @var \App\Model\Service\Cat $catService */
            $catService = $this->context->getService('Cat');

            /** @var \App\Model\Service\Vaccination $vaccinationService */
            $vaccinationService = $this->context->getService('Vaccination');
            $vaccination = $vaccinationService->getById($vid);
            if($vaccination !== false) {
                $catService->deleteVaccination($this->cat, $vaccination);
                $this->flashMessage('Očkování kočky bylo smazáno', 'info');
            } else {
                $this->flashMessage('Očkování neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Kočka neexistuje', 'warning');
        }

        $this->redirect('edit', ['id' => $cid]);
    }

    /** @secured */
    public function handleDeleteColor($cid, $coid)
    {
        if($this->cat) {
            /** @var \App\Model\Service\Cat $catService */
            $catService = $this->context->getService('Cat');

            /** @var \App\Model\Service\Color $colorService */
            $colorService = $this->context->getService('Color');
            $color = $colorService->getById($coid);
            if($color !== false) {
                $catService->deleteColor($this->cat, $color);
                $this->flashMessage('Barva kočky byla smazána', 'info');
            } else {
                $this->flashMessage('Barva neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Kočka neexistuje', 'warning');
        }

        $this->redirect('edit', ['id' => $cid]);
    }
}
