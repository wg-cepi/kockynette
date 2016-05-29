<?php
namespace App\Model\Collection;

class Cat extends AbstractCollection
{
    /** @var  \App\Model\Service\Cat */
    private $catService;

    /** @var  \App\Model\Service\Cat */
    private $depoService;

    public function __construct(
        \App\Model\Service\Cat $catService
        //\App\Model\Service\Depozitum $depoService
    )
    {
        //$this->depoService = $depoService;
        $this->catService = $catService;
    }

    public function setData($data)
    {
        /** @var \App\Model\Table\Cat $cat */
        foreach ($data as $cat) {
            $this->data['items'][$cat->id]['cat'] = $cat;
            $this->data['ids'][$cat->id] = $cat->id;
        }
        $this->data['count'] = count($data);
    }

    public function getCat($id)
    {
        return $this->get($id, 'cat');
    }

}