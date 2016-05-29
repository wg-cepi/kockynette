<?php
namespace App\AdminModule\Signals;

use App\Components\Signal\AbstractFormSignal;
use Cepi\DateUtils;
use Cepi\StringUtils;

class AddCatSignal extends AbstractFormSignal
{
    public function perform()
    {
        $result = self::OK;
        $values = $this->form->getValues();

        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->presenter->context->getService('Cat');

        $allColors = $this->presenter->getRequest()->getPost('colors') ? $this->presenter->getRequest()->getPost('colors') : [];
        $allVacs = $this->presenter->getRequest()->getPost('vaccinations') ? $this->presenter->getRequest()->getPost('vaccinations') : [];
        $allHdcps = $this->presenter->getRequest()->getPost('handicaps') ? $this->presenter->getRequest()->getPost('handicaps') : [];

        $newVac = array_diff($allVacs, $values->vaccinations);
        $newHdcps = array_diff($allHdcps, $values->handicaps);
        $newColors = array_diff($allColors, $values->colors);

        /** @var \App\Model\Service\Color $colorService */
        $colorService = $this->presenter->context->getService('Color');
        $catColors = $values->colors;
        foreach ($newColors as $key => $name) {
            $catColors[] = $colorService->insert([
                'name' => StringUtils::caws($name)
            ])->id;
        }

        /** @var \App\Model\Service\Vaccination $vacService */
        $vacService = $this->presenter->context->getService('Vaccination');
        $catVacs = $values->vaccinations;
        foreach ($newVac as $key => $name) {
            $catVacs[] = $vacService->insert([
                'name' => StringUtils::tmws($name)
            ])->id;
        }

        /** @var \App\Model\Service\Vaccination $hdcpsService */
        $hdcpService = $this->presenter->context->getService('Handicap');
        $catHdcps = $values->handicaps;
        foreach ($newHdcps as $key => $name) {
            $catHdcps[] = $hdcpService->insert([
                'name' => StringUtils::tmws($name)
            ])->id;
        }

        $cat = $catService->insert([
            'name' => StringUtils::tmws($values->name),
            'born' => DateUtils::Ymd($values->born),
            'gender' => $values->gender,
            'castrated' => $values->castrated,
            'depozitum_id' => $values->depozitum
        ]);

        $this->presenter->cat = $cat;
        $signal = new CatImageUploadSignal($this->form, $this->presenter);
        $signal->setVerbose(false);
        $result = $signal->execute();

        $catService->addColors($cat, $catColors);
        $catService->addHandicaps($cat, $catHdcps);
        $catService->addVaccinations($cat, $catVacs);

        $this->presenter->flashMessage('Kočka přidána');

        return $result;
    }

}