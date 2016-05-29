<?php

namespace App\Model\Service;

use Cepi\StringUtils;
use Nette\Database\SqlLiteral;

class Cat extends AbstractService
{

    /** @var  \App\Model\Table\Cat @inject */
    public $cat;

    /** @var  \App\Model\Table\Cat_X_Color @inject */
    public $catColors;

    /** @var  \App\Model\Table\Cat_X_Handicap @inject */
    public $catHdcps;

    /** @var  \App\Model\Table\Cat_X_Vaccination @inject */
    public $catVacs;

    /** @var  \App\Model\Table\Image @inject */
    public $catImages;

    public function __construct(
        \App\Model\Table\Cat $cat,
        \App\Model\Table\Cat_X_Color $catColors,
        \App\Model\Table\Cat_X_Handicap $catHdcps,
        \App\Model\Table\Cat_X_Vaccination $catVacs,
        \App\Model\Table\Cat_X_Image $catImages

    )
    {
        $this->cat = $cat;
        $this->catColors = $catColors;
        $this->catHdcps = $catHdcps;
        $this->catVacs = $catVacs;
        $this->catImages = $catImages;
    }


    public function getById($id)
    {
        return $this->cat->getById($id);
    }

    public function getAll()
    {
        return $this->cat->getAll();
    }

    public function getAllByDepozitumIds($depoIds)
    {
        return $this->cat->in('depozitum_id', $depoIds);
    }

    public function getHandicaps($cat)
    {
        $result = [];
        foreach ($cat->related('cat_x_handicap')->where('cat_id = ?', $cat->id) as $item) {
            $handicap = $item->handicap;
            $result[$handicap->id] = $handicap;
        }

        return $result;
    }

    public function getVaccinations($cat)
    {
        $result = [];
        foreach ($cat->related('cat_x_vaccination')->where('cat_id = ?', $cat->id) as $item) {
            $vaccination = $item->vaccination;
            $result[$vaccination->id] = $vaccination;
        }

        return $result;
    }

    public function getColors($cat)
    {
        $result = [];
        foreach ($cat->related('cat_x_color')->where('cat_id = ?', $cat->id) as $item) {
            $color = $item->color;
            $result[$color->id] = $color;
        }

        return $result;
    }

    public function getImages($cat)
    {
        $result = [];
        foreach ($cat->related('cat_x_image')->where('cat_id = ?', $cat->id) as $item) {
            $image = $item->image;
            $result[$image->id] = $image;
        }

        return $result;
    }

    public function getCatColorIds($cat)
    {
        $result = [];
        foreach ($cat->related('cat_x_color')->where('cat_id = ?', $cat->id) as $item) {
            $color = $item->color;
            $result[$color->id] = $color->id;
        }

        return $result;
    }

    public function getCatsWithoutDepoAsIdNamePairs()
    {
        $result = [];
        foreach ($this->cat->where('depozitum_id IS NULL') as $cat) {
            $result[$cat->id] = $cat->name;
        }

        return $result;
    }

    public function getNewest($limit = 10)
    {
        return $this->cat->limit($limit)->order('created ASC');
    }


    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow[]
     */
    public function insert($values)
    {
        return $this->cat->insert($values);
    }


    public function addColors($cat, $colors)
    {
        $result = [];
        foreach ($colors as $key => $colorId) {
            $result[] = $this->catColors->insert([
                'cat_id' => $cat->id,
                'color_id' => $colorId
            ]);
        }
        return $result;
    }

    public function addHandicaps($cat, $handicaps)
    {
        $result = [];
        foreach ($handicaps as $key => $handicapId) {
            $result[] = $this->catHdcps->insert([
                'cat_id' => $cat->id,
                'handicap_id' => $handicapId
            ]);
        }
        return $result;
    }

    public function addVaccinations($cat, $vaccinations)
    {
        $result = [];
        foreach ($vaccinations as $key => $vacId) {
            $result[] = $this->catVacs->insert([
                'cat_id' => $cat->id,
                'vaccination_id' => $vacId
            ]);
        }
        return $result;
    }

    public function addImages($cat, $images)
    {
        $result = [];
        foreach ($images as $key => $imageId) {
            $result[] = $this->catImages->insert([
                'cat_id' => $cat->id,
                'image_id' => $imageId
            ]);
        }
        return $result;
    }

    public function delete($cat)
    {
        $catColors = $this->catColors->where('cat_id = ?', $cat->id);
        $catHandicaps = $this->catHdcps->where('cat_id = ?', $cat->id);
        $catVaccinations = $this->catVacs->where('cat_id = ?', $cat->id);
        $catImages = $this->catImages->where('cat_id = ?', $cat->id);
        //TODO delete images from disk

        $catColors->delete();
        $catHandicaps->delete();
        $catVaccinations->delete();
        $catImages->delete();

        $cat->delete();
    }

    public function deleteImage($cat, $image)
    {
        $catImage = $this->catImages->where('cat_id = ? AND image_id = ?', $cat->id, $image->id);
        return $catImage->delete();
    }

    public function deleteHandicap($cat, $handicap)
    {
        $catHandicap = $this->catHdcps->where('cat_id = ? AND handicap_id = ?', $cat->id, $handicap->id);
        return $catHandicap->delete();
    }

    public function deleteVaccination($cat, $vaccination)
    {
        $catVaccination = $this->catVacs->where('cat_id = ? AND vaccination_id = ?', $cat->id, $vaccination->id);
        return $catVaccination->delete();
    }

    public function deleteColor($cat, $color)
    {
        $catColor = $this->catColors->where('cat_id = ? AND color_id = ?', $cat->id, $color->id);
        return $catColor->delete();
    }

    public function filter($filter)
    {
        $params = [];
        $where = $AND = $select = $having = '';

        if (isset($filter['name'])) {
            $name = StringUtils::tmws($filter['name']);
            if ($name != '') {
                $where .= $AND . ' LOWER(cat.name) LIKE ?';
                $params[] = '%' . mb_strtolower($name) . '%';
                $AND = ' AND';
            }
        }

        if (!empty($filter['colors'])) {
            $where .= $AND . ' :cat_x_color.color_id IN (?)';
            $params[] = $filter['colors'];
            $AND = ' AND';
        }

        if (!empty($filter['depos'])) {
            $where .= $AND . ' cat.depozitum_id IN (?)';
            $params[] = $filter['depos'];
            $AND = ' AND';
        }

        if (isset($filter['gender']) && $filter['gender'] !== 'dc') {
            $where .= $AND . ' gender = ?';
            $params[] = $filter['gender'];
            $AND = ' AND';
        }

        if (isset($filter['castrated']) && $filter['castrated'] !== 'dc') {
            $where .= $AND . ' castrated = ?';
            $params[] = $filter['castrated'];
            $AND = ' AND';
        }

        if (isset($filter['handicapped']) && $filter['handicapped'] !== 'dc') {
            if ($filter['handicapped'] === 1) {
                $where .= $AND . ' :cat_x_handicap.cat_id = cat.id';
            } else {
                $select = 'cat.*, (SELECT COUNT(id) FROM `cat_x_handicap` WHERE `cat_x_handicap`.`cat_id` = `cat`.`id`) AS hdcnt';
                $having = 'hdcnt = 0';
            }
        }

        if ($where == '' && $select == '') {
            return $this->cat->getAll();
        } else {
            if ($select != '') {
                if ($having) {
                    return $this->cat->select($select)->where($where, ...$params)->having($having);
                } else {
                    return $this->cat->select($select)->where($where, ...$params);
                }
            } else {
                return $this->cat->where($where, ...$params);
            }

        }
    }
}
