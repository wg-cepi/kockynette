<?php

namespace App\Model\Service;

class Depozitum extends AbstractService
{

    /** @var  \App\Model\Table\Depozitum @inject */
    public $depozitum;

    /** @var  \App\Model\Table\Depozitum_X_Contact @inject */
    public $depozitumContacts;

    /** @var  \App\Model\Table\Cat @inject */
    public $cat;

    public function __construct(
        \App\Model\Table\Depozitum $depozitum,
        \App\Model\Table\Depozitum_X_Contact $depozitumContacts,
        \App\Model\Table\Cat $cat
    )
    {
        $this->depozitum = $depozitum;
        $this->depozitumContacts = $depozitumContacts;
        $this->cat = $cat;
    }


    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->depozitum->getById($id);
    }

    /**
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->depozitum->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        /** @var \App\Model\Table\Depozitum $item */
        foreach($this->depozitum->getAll() as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getCats($depozitum)
    {
        return $this->cat->where('depozitum_id = ?', $depozitum->id);
    }

    public function getCatsCount($depozitum)
    {
        return $this->cat->where('depozitum_id = ?', $depozitum->id)->count();
    }

    public function getContacts($depozitum)
    {
        $result = [];
        foreach($depozitum->related('depozitum_x_contact') as $item) {
            $result[] = $item->contact;
        }

        return $result;
    }

    public function getContactIds($depozitum)
    {
        $result = [];
        foreach($depozitum->related('depozitum_x_contact') as $item) {
            $result[] = $item->contact->id;
        }

        return $result;
    }


    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->depozitum->insert($values);
    }

    public function addContacts($depozitum, $contacts)
    {
        $result = [];
        foreach($contacts as $key => $contactId) {
            $result[] = $this->depozitumContacts->insert([
                'depozitum_id' => $depozitum->id,
                'contact_id' => $contactId
            ]);
        }
        return $result;
    }

    public function addCats($depozitum, $cats)
    {
        return $this->cat->where('id IN ?', $cats)->update([
            'depozitum_id' => $depozitum->id
        ]);
    }

    public function delete($depozitum){
        $depContacts = $this->depozitumContacts->where('depozitum_id = ?', $depozitum->id);
        $depContacts->delete();

        $depozitum->delete();
    }

    public function deleteContact($depozitum, $contact)
    {
        $depContact = $this->depozitumContacts->where('depozitum_id = ? AND contact_id = ?', $depozitum->id, $contact->id);
        $depContact->delete();
    }

    public function deleteCat($depozitum, $cat)
    {
        return $this->cat->where('depozitum_id = ? AND id = ?', $depozitum->id, $cat->id)
            ->update([
                'depozitum_id' => null
            ]);
    }

}
