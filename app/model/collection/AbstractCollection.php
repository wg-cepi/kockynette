<?php
namespace App\Model\Collection;

abstract class AbstractCollection
{
    protected $data = null;

    public function isEmpty()
    {
        return empty($this->data['items']);
    }

    public function getCount($index = '')
    {
        if($index === '') {
            return $this->data['count'];
        } else {
            return isset($this->data[$index]['count']) ? $this->data[$index]['count'] : 0;
        }
    }

    public function getItems()
    {
        return empty($this->data['ids']) ? null : $this->data['items'];
    }

    public function getIds()
    {
        return empty($this->data['ids']) ? null : $this->data['ids'];
    }

    public function get($id, $index)
    {
        return empty($this->data['items'][$id][$index]) ? null : $this->data['items'][$id][$index];
    }
}