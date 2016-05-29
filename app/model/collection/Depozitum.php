<?php
namespace App\Model\Collection;

use App\Model\Table\Cat;
use Nette\Database\Table\Selection;

class Depozitum extends AbstractCollection
{
    /** @var  \App\Model\Service\Cat */
    private $catService;

    /** @var  \App\Model\Service\Cat */
    private $depoService;

    public function __construct(
        \App\Model\Service\Cat $catService,
        \App\Model\Service\Depozitum $depoService
    )
    {
        $this->depoService = $depoService;
        $this->catService = $catService;
    }

    public function setData($data)
    {
        /** @var \App\Model\Table\Depozitum $depo */
        foreach($data as $depo)
        {
            $this->data['items'][$depo->id]['depo'] = $depo;
            $this->data['ids'][$depo->id] = $depo->id;
        }
        $this->data['count'] = count($data);

    }

    public function withCats()
    {
        if($this->isEmpty()) return $this;

        /** @var Selection $cats */
        $cats = $this->catService->getAllByDepozitumIds($this->getIds());

        foreach($this->getItems() as $depoId => $item) {
            $result = [];
            /** @var Cat $cat */
            foreach($cats as $cat) {
                if($cat->depozitum_id == $depoId) {
                    $result[$cat->id] = $cat;
                }
            }
            $this->data['items'][$depoId]['cats'] = $result;
            $this->data['items'][$depoId]['cats']['count'] = count($result);
        }
    }

    public function getDepo($id)
    {
        return $this->get($id, 'depo');
    }

    public function getCats($id) {
        return $this->get($id, 'cats');
    }

    public function getCatsCount($id)
    {
        $cats = $this->get($id, 'cats');
        return $cats !== null ? $cats['count'] : 0;
    }
}