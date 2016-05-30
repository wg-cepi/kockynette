<?php

namespace App\AdminModule\Presenters;


use App\Components\Form\ContactForm;
use App\Components\Traits\Depozitum\TDepozitumDefault;
use App\Components\Traits\Depozitum\TDepozitumList;
use App\Model\Table\Address;
use App\Model\Table\Depozitum;
use Cepi\StringUtils;
use Nette;


class DepozitumPresenter extends BasePresenter
{
    /** @var  Depozitum */
    private $depo;

    /** @var  Address */
    private $depoAddress;

    private $contacts = [];

    public function actionAdd()
    {

    }

    public function createComponentAddDepozitumForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('name', 'Název')
            ->setRequired('Prosím vyplňte název depozita');

        $form->addText('capacity', 'Kapacita')
            ->setType('number')
            ->addRule(Nette\Application\UI\Form::INTEGER, 'Kapacita musí být číslo')
            ->addRule(Nette\Application\UI\Form::MIN, 'Kapacita musí být větší nebo rovna 0', 0)
            ->setDefaultValue(10)
            ->setRequired('Kapacita musí být vyplněna');

        $form->addRadioList('state', 'Stav', [
            'open' => 'Otevřené',
            'closed' => 'Zavřené'
        ])
            ->setDefaultValue('open')
            ->setRequired('Prosím vyberte stav');

        /** @var \App\Model\Service\Contact $contactService */
        $contactService = $this->context->getService('Contact');
        $form->addMultiSelect('contacts', 'Kontaktní osoby', $contactService->getAllAsIdNamePairs());

        $form->addText('street', 'Ulice, čp.')
            ->setRequired('Prosím vyplňte ulici a číslo popisné');
        $form->addText('city', 'Město')
            ->setRequired('Prosím vyplňte město');
        $form->addText('zip', 'PSČ')
            ->addRule(Nette\Forms\Form::PATTERN, 'Zadejte PSČ ve formátu 12345 nebo 123 45', '[0-9]{3}\s?[0-9]{2}')
            ->setRequired('Prosím vyplňte PSČ');

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addDepozitumFormSuccess');
        return $form;
    }

    public function addDepozitumFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $allContacts = $this->getHttpRequest()->getPost('contacts', []);
        $newContacts = array_diff($allContacts, $values->contacts);

        /** @var \App\Model\Service\Contact $contactsService */
        $contactsService = $this->context->getService('Contact');
        $depozitumContacts = $values->contacts;
        foreach($newContacts as $key => $name) {
            $name = StringUtils::tmws($name);
            $exploded = explode(' ', $name);
            if(count($exploded) < 2) {
                list($firstname, $lastname) = ['', $name];
            } else {
                list($firstname, $lastname) = $exploded;
            }

            $depozitumContacts[] = $contactsService->insert([
                'firstname' => $firstname,
                'lastname' => $lastname
            ])->id;
        }

        /** @var \App\Model\Service\Address $addressService */
        $addressService = $this->context->getService('Address');

        $address = $addressService->insert([
            'street' => StringUtils::tmws($values->street),
            'city' => StringUtils::tmws($values->city),
            'zip' => StringUtils::tmws($values->zip)
        ]);

        /** @var \App\Model\Service\Depozitum $depozitumService */
        $depozitumService = $this->context->getService('Depozitum');
        $depozitum = $depozitumService->insert([
            'name' => StringUtils::tmws($values->name),
            'capacity' => $values->capacity,
            'state' => $values->state,
            'address_id' => $address->id
        ]);

        $depozitumService->addContacts($depozitum, $depozitumContacts);

        $this->flashMessage('Depozitum přidáno');
        $this->redirect('list');
    }

    public function actionEdit($id)
    {
        /** @var \App\Model\Service\Depozitum $depService */
        $depService = $this->context->getService('Depozitum');
        $depozitum = $depService->getById($id);

        if($depozitum === false) {
            throw new Nette\Application\BadRequestException('Depozitum does not exist', 404);
        } else {
            $this->depo = $depozitum;
            $this->contacts = $depService->getContacts($this->depo);
            $this->depoAddress = $depozitum->address;

            $this->template->depo = $depozitum;
            $this->template->contacts = $this->contacts;
        }
    }

    public function createComponentEditDepozitumForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('name', 'Název')
            ->setDefaultValue($this->depo->name)
            ->setRequired('Prosím vyplňte název depozita');

        $form->addText('capacity', 'Kapacita')
            ->setType('number')
            ->addRule(Nette\Application\UI\Form::INTEGER, 'Kapacita musí být číslo')
            ->addRule(Nette\Application\UI\Form::MIN, 'Kapacita musí být větší nebo rovna 0', 0)
            ->setDefaultValue($this->depo->capacity)
            ->setRequired('Kapacita musí být vyplněna');

        $form->addRadioList('state', 'Stav', [
            'open' => 'Otevřené',
            'closed' => 'Zavřené'
        ])
            ->setDefaultValue($this->depo->state)
            ->setRequired('Prosím vyberte stav');

        $form->addText('street', 'Ulice, čp.')
            ->setRequired('Prosím vyplňte ulici a číslo popisné');

        $form->addText('city', 'Město')
            ->setRequired('Prosím vyplňte město');

        $form->addText('zip', 'PSČ')
            ->addRule(Nette\Forms\Form::PATTERN, 'Zadejte PSČ ve formátu 12345 nebo 123 45', '[0-9]{3}\s?[0-9]{2}')
            ->setRequired('Prosím vyplňte PSČ');

        if($this->depoAddress) {
            $form->setDefaults([
                'street' => $this->depoAddress->street,
                'city' => $this->depoAddress->city,
                'zip' => $this->depoAddress->zip
            ]);
        }

        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = array($this, 'editDepozitumFormSuccess');
        return $form;

    }

    public function editDepozitumFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();
        $depoData = [
            'name' =>  StringUtils::tmws($values->name),
            'capacity' =>  $values->capacity,
            'state' =>  $values->state,
        ];

        if($this->depoAddress) {
            $this->depoAddress->update([
                'street' => StringUtils::tmws($values->street),
                'city' => StringUtils::tmws($values->city),
                'zip' => StringUtils::tmws($values->zip)
            ]);
        } else {
            /** @var \App\Model\Service\Address $addressService */
            $addressService = $this->context->getService('Address');

            $address = $addressService->insert([
                'street' => StringUtils::tmws($values->street),
                'city' => StringUtils::tmws($values->city),
                'zip' => StringUtils::tmws($values->zip)
            ]);

           $depoData['address_id'] = $address->id;
        }

        $this->depo->update($depoData);


        $this->flashMessage('Depozitum upraveno');
        $this->redirect('edit', ['id' => $this->depo->id]);
    }

    public function createComponentAddDepozitumContactForm()
    {
        $form = new Nette\Application\UI\Form();

        /** @var \App\Model\Service\Contact $contactService */
        $contactService = $this->context->getService('Contact');

        $contacts = [];
        if($this->contacts) {
            $contactIds = [];
            foreach($this->contacts as $contact) {
                $contactIds[] = $contact->id;
            }
            foreach($contactService->getDiff($contactIds) as $contact) {
                $contacts[$contact->id] = $contact->firstname . ' ' . $contact->lastname;
            }
        } else {
            $contacts = $contactService->getAllAsIdNamePairs();
        }

        $form->addMultiSelect('contacts', 'Kontaktní osoby', $contacts);

        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addDepozitumContactFormSuccess');
        return $form;
    }

    public function addDepozitumContactFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $allContacts = $this->getHttpRequest()->getPost('contacts', []);
        $newContacts = array_diff($allContacts, $values->contacts);

        /** @var \App\Model\Service\Contact $contactsService */
        $contactsService = $this->context->getService('Contact');
        $depozitumContacts = $values->contacts;
        foreach($newContacts as $key => $name) {
            $name = StringUtils::tmws($name);
            $exploded = explode(' ', $name);
            if(count($exploded) < 2) {
                list($firstname, $lastname) = ['', $name];
            } else {
                list($firstname, $lastname) = $exploded;
            }

            $depozitumContacts[] = $contactsService->insert([
                'firstname' => $firstname,
                'lastname' => $lastname
            ])->id;
        }

        /** @var \App\Model\Service\Depozitum $depozitumService */
        $depozitumService = $this->context->getService('Depozitum');
        try {
            $depozitumService->addContacts($this->depo, $depozitumContacts);
            $this->flashMessage('Kontakty přidány');
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Nelze přidat stejné kontaky', 'warning');
        }

        $this->redirect('edit', ['id' => $this->depo->id]);

    }

    public function actionAddCats($id)
    {
        /** @var \App\Model\Service\Depozitum $depService */
        $depService = $this->context->getService('Depozitum');
        $depozitum = $depService->getById($id);

        if($depozitum === false) {
            throw new Nette\Application\BadRequestException('Depozitum does not exist', 404);
        } else {
            $this->depo = $depozitum;
            $this->template->depo = $depozitum;
        }
    }

    public function createComponentAddCatsForm()
    {
        $form = new Nette\Application\UI\Form();

        /** @var \App\Model\Service\Cat $catService */
        $catService = $this->context->getService('Cat');

        $form->addMultiSelect('cats', 'Kočky', $catService->getCatsWithoutDepoAsIdNamePairs())
            ->setRequired('Vyberte alespoň jednu kočku');

        $form->addSubmit('submit', 'Umístit');
        $form->onSuccess[] = array($this, 'addCatsFormSuccess');

        return $form;
    }

    public function addCatsFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();
        /** @var \App\Model\Service\Depozitum $depozitumService */
        $depozitumService = $this->context->getService('Depozitum');

        $inputCatsCount = count($values->cats);
        $catsCount = $depozitumService->getCatsCount($this->depo);

        $newCats = [];
        if($catsCount < $this->depo->capacity && $this->depo->state == 'open') {
            foreach ($values->cats as $catId) {
                if($catsCount < $this->depo->capacity) {
                    $newCats[] = $catId;
                    $catsCount++;
                }
            }
        }

        $depozitumService->addCats($this->depo, $newCats);

        if($inputCatsCount !== count($newCats)) {
            $this->flashMessage('Nějaké kočky nebyly přidány, jelikož by byla překročena kapacita depozita', 'warning');
        } else {
            $this->flashMessage('Kočky přidány', 'success');
        }

        $this->redirect(':Common:Depozitum:default', ['id' => $this->depo->id]);
    }

    public function actionDelete($id)
    {
        /** @var \App\Model\Service\Depozitum $depozitumService */
        $depozitumService = $this->context->getService('Depozitum');
        $depozitum = $depozitumService->getById($id);
        if($depozitum !== false) {
            $this->depo = $depozitum;
        } else {
            throw new Nette\Application\BadRequestException('Depozitum does not exist', 404);
        }
    }

    /** @secured */
    public function handleDelete($id)
    {
        /** @var \App\Model\Service\Depozitum $depozitumService */
        $depozitumService = $this->context->getService('Depozitum');

        try{
            $depozitumService->delete($this->depo);
            $this->flashMessage('Depozitum smazáno');
        } catch (Nette\Database\ForeignKeyConstraintViolationException $e) {
            $this->flashMessage('Nelze smazat depozitum ve kterém jsou kočky', 'warning');
        }

        $this->redirect(':Common:Depozitum:list');

    }

    /** @secured */
    public function handleDeleteContact($did, $cid)
    {
        if($this->depo->id == $did) {
            /** @var \App\Model\Service\Depozitum $depozitumService */
            $depozitumService = $this->context->getService('Depozitum');

            /** @var \App\Model\Service\Contact $contactService */
            $contactService = $this->context->getService('Contact');
            $contact = $contactService->getById($cid);
            if($contact !== false) {
                $depozitumService->deleteContact($this->depo, $contact);
                $this->flashMessage('Kontakt depozita byl smazán', 'info');
            } else {
                $this->flashMessage('Kontakt neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Depozitum neexistuje', 'warning');
        }

        $this->redirect('edit', ['id' => $did]);
    }

    public function actionDeleteCat($did, $cid)
    {
        /** @var \App\Model\Service\Depozitum $depService */
        $depService = $this->context->getService('Depozitum');
        $depozitum = $depService->getById($did);

        if($depozitum === false) {
            throw new Nette\Application\BadRequestException('Depozitum does not exist', 404);
        } else {
            $this->depo = $depozitum;
        }
    }

    /** @secured */
    public function handleDeleteCat($did, $cid)
    {
        if($this->depo) {
            /** @var \App\Model\Service\Depozitum $depozitumService */
            $depozitumService = $this->context->getService('Depozitum');

            /** @var \App\Model\Service\Cat $catService */
            $catService = $this->context->getService('Cat');
            $cat = $catService->getById($cid);
            if($cat !== false) {
                $depozitumService->deleteCat($this->depo, $cat);
                $this->flashMessage('Kočka byla smazána z depozita', 'info');
            } else {
                $this->flashMessage('Kočka neexistuje', 'warning');
            }
        } else {
            $this->flashMessage('Depozitum neexistuje', 'warning');
        }

        $this->redirect(':Common:Depozitum:default', ['id' => $this->depo->id]);
    }
}
