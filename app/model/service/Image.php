<?php

namespace App\Model\Service;

class Image extends AbstractService
{
    /** @var  \App\Model\Table\Image @inject */
    public $image;

    public function __construct(
        \App\Model\Table\Image $image
    )
    {
        $this->image = $image;
    }


    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->image->getById($id);
    }

    /**
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->image->getAll();
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->image->insert($values);
    }

}
