<?php

namespace App\Model\Service;

class Color extends AbstractService
{

    /** @var  \App\Model\Table\Color @inject */
    public $color;

    public function __construct(\App\Model\Table\Color $color)
    {
        $this->color = $color;
    }


    public function getById($id)
    {
        return $this->color->getById($id);
    }

    public function getAll()
    {
        return $this->color->getAll();
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        foreach($this->color->order('name ASC') as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getDiff($colorIds)
    {
        return $this->color->where('id NOT IN (?)', $colorIds);
    }

    public function getCats($color)
    {
        $result = [];
        foreach($color->related('cat_x_color')->where('color_id = ?', $color->id)->group('cat_id') as $item) {
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
        return $this->color->insert($values);
    }

    public function delete()
    {
        return $this->color->delete();
    }

}
