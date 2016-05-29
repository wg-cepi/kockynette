<?php

namespace App\Model\Service;

class Address extends AbstractService
{

    /** @var  \App\Model\Table\Address @inject */
    public $address;

    public function __construct(\App\Model\Table\Address $address)
    {
        $this->address = $address;
    }


    public function getById($id)
    {
        return $this->address->getById($id);
    }

    public function getAll()
    {
        return $this->address->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        /** @var  \App\Model\Table\Address $item */
        foreach($this->address->getAll() as $item) {
            $result[$item->id] = $item->street . ', ' . $item->city . ' ,' . $item->zip;
        }
        return $result;
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->address->insert($values);
    }

}
