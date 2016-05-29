<?php

namespace App\AdminModule\Presenters;

use App\Components\Form\ContactForm;
use \Cepi\StringUtils;
use Nette;


class ContactPresenter extends BasePresenter
{
    /** @var \App\Model\Table\Contact $contact */
    private $contact = null;


    public function actionDefault($id)
    {
        /** @var \App\Model\Service\Depozitum $contactService */
        $contactService = $this->context->getService('Contact');
        $contact = $contactService->getById($id);
        if($contact !== false) {
            $this->contact = $contact;
            $this->template->contact = $contact;
        } else {
            throw new Nette\Application\BadRequestException('Contact does not exist', 404);
        }
    }

    public function actionList()
    {
        $this->template->contacts = $this->context->getService('Contact')->getAll();
    }

    public function actionAdd()
    {

    }

    public function createComponentAddContactForm()
    {
        $form = new ContactForm();
        $form->addSubmit('submit', 'Přidat');

        $form->onSuccess[] = array($this, 'addContactFormSuccess');
        return $form;
    }

    public function addContactFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $this->context->getService('Contact')->insert([
            'firstname' =>  StringUtils::tmws($values->firstname),
            'lastname' =>  StringUtils::tmws($values->lastname),
            'email' =>  StringUtils::tmws($values->email),
            'phone' =>  StringUtils::caws($values->phone),
        ]);

        $this->flashMessage('Kontakt byl přidán');
        $this->redirect('list');
    }

    public function actionEdit($id)
    {
        /** @var \App\Model\Service\Depozitum $contactService */
        $contactService = $this->context->getService('Contact');
        $contact = $contactService->getById($id);
        if($contact !== false) {
            $this->contact = $contact;
            $this->template->contact = $contact;
        } else {
            throw new Nette\Application\BadRequestException('Contact does not exist', 404);
        }
    }

    public function createComponentEditContactForm()
    {
        $form = new ContactForm();
        $form->setDefaults([
           'firstname' => $this->contact->firstname,
           'lastname' => $this->contact->lastname,
           'email' => $this->contact->email,
           'phone' => $this->contact->phone,
        ]);

        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = array($this, 'editContactFormSuccess');
        return $form;
    }

    public function editContactFormSuccess(Nette\Forms\Form $form)
    {
        $values = $form->getValues();

        $this->contact->update([
            'firstname' =>  StringUtils::tmws($values->firstname),
            'lastname' =>  StringUtils::tmws($values->lastname),
            'email' =>  StringUtils::tmws($values->email),
            'phone' =>  StringUtils::caws($values->phone),
        ]);

        $this->flashMessage('Kontakt byl upraven');
        $this->redirect('list');
    }

    /** @secured */
    public function handleDelete($id)
    {
        /** @var \App\Model\Service\Depozitum $contactService */
        $contactService = $this->context->getService('Contact');
        $contact = $contactService->getById($id);
        if($contact !== false) {
            $contactService->delete($contact);
            $this->flashMessage('Kontakt smazán');
            $this->redirect('list');
        } else {
            throw new Nette\Application\BadRequestException('Contact does not exist', 404);
        }
    }
}
