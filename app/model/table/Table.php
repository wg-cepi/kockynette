<?php

namespace App\Model\Table;

use Nette;


/**
 * Class Repository
 *
 * @package App\Model
 */
abstract class Table extends \Nette\Object
{
    /** @var Nette\Database\Context */
    protected $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    private function getTableName()
    {
        $x = get_class($this);
        $x = explode('\\', $x);
        return lcfirst(end($x));
    }

    protected function getTable($table = false)
    {
        if($table){
            return $this->database->table($table);
        }else{
            return $this->database->table($this->getTableName());
        }

    }

    public function limit(){
        return call_user_func_array(array($this->getTable(), 'limit'), func_get_args());
    }

    public function select(){
        return call_user_func_array(array($this->getTable(), 'select'), func_get_args());
    }

    public function where(){
        return call_user_func_array(array($this->getTable(), 'where'), func_get_args());
    }

    public function order(){
        return call_user_func_array(array($this->getTable(), 'order'), func_get_args());
    }

    public function delete(){
        return call_user_func_array(array($this->getTable(), 'delete'), func_get_args());
    }

    public function in($column, $array)
    {
        return $this->where("$column IN ?", $array);
    }

    public function insert($data){
        foreach($data as &$item)
        {
            if(is_string($item)) {
                $item = \Cepi\StringUtils::tmws($item);
            }
        }
        return $this->getTable()->insert($data);
    }

    /**
     * @param $id
     * @return Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->getTable()->get($id);
    }

    /**
     * @return array|Nette\Database\Table\IRow[]
     */
    public function getAll()
    {
        return $this->getTable()->where('1=1');
    }


}