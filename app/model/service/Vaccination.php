<?php

namespace App\Model\Service;

class Vaccination extends AbstractService
{

    /** @var  \App\Model\Table\Vaccination @inject */
    public $vaccination;

    public function __construct(\App\Model\Table\Vaccination $vaccination)
    {
        $this->vaccination = $vaccination;
    }


    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->vaccination->getById($id);
    }

    /**
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->vaccination->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        foreach ($this->vaccination->order('severity DESC') as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getDiff($vaccinationIds)
    {
        return $this->vaccination->where('id NOT IN (?)', $vaccinationIds);
    }

    public function getCats($vaccination)
    {
        $result = [];
        foreach ($vaccination->related('cat_x_vaccination')->where('vaccination_id = ?', $vaccination->id)->group('cat_id') as $item) {
            $cat = $item->cat;
            $result[$cat->id] = $cat;
        }

        return $result;
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->vaccination->insert($values);
    }

}
