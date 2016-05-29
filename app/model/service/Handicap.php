<?php

namespace App\Model\Service;

class Handicap extends AbstractService
{

    /** @var  \App\Model\Table\Handicap @inject */
    public $handicap;

    public function __construct(\App\Model\Table\Handicap $handicap)
    {
        $this->handicap = $handicap;
    }


    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->handicap->getById($id);
    }

    /**
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->handicap->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        foreach($this->handicap->order('severity DESC') as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getDiff($handicapIds)
    {
        return $this->handicap->where('id NOT IN (?)', $handicapIds);
    }

    public function getCats($handicap)
    {
        $result = [];
        foreach($handicap->related('cat_x_handicap')->where('handicap_id = ?', $handicap->id)->group('cat_id') as $item) {
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
        return $this->handicap->insert($values);
    }

}
