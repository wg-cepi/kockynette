<?php

namespace App\Model\Service;

class User extends AbstractService
{

    /** @var  \App\Model\Table\User @inject */
    public $user;

    public function __construct(\App\Model\Table\User $user)
    {
        $this->user = $user;
    }


    public function getById($id)
    {
        return $this->user->getById($id);
    }

    public function getAll()
    {
        return $this->user->getAll();
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->user->insert($values);
    }

    public function register($data)
    {
        $user = $this->user->insert($data);
        return $user;
    }

}
