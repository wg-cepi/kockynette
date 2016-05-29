<?php

namespace App\Model\Service;

class Contact extends AbstractService
{

    /** @var  \App\Model\Table\Contact @inject */
    public $contact;

    /** @var  \App\Model\Table\Depozitum_X_Contact @inject */
    public $depozitumContacts;

    public function __construct(
        \App\Model\Table\Contact $contact,
        \App\Model\Table\Depozitum_X_Contact $depozitumContacts
    )
    {
        $this->contact = $contact;
        $this->depozitumContacts = $depozitumContacts;
    }


    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->contact->getById($id);
    }

    /**
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->contact->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        foreach($this->contact->order('lastName ASC') as $item) {
            $result[$item->id] = $item->firstname . ' ' . $item->lastname;
        }
        return $result;
    }

    public function getDiff($contactIds) {
        return $this->contact->where('id NOT IN (?)', $contactIds);
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->contact->insert($values);
    }

    public function delete($contact){
        $depContacts = $this->depozitumContacts->where('contact_id = ?', $contact->id);
        $depContacts->delete();

        $contact->delete();
    }

}
