<?php

namespace App\Model\Service;

class Article extends AbstractService
{

    /** @var  \App\Model\Table\Article @inject */
    public $article;

    public function __construct(\App\Model\Table\Article $article)
    {
        $this->article = $article;
    }


    public function getById($id)
    {
        return $this->article->getById($id);
    }

    public function getAll()
    {
        return $this->article->getAll();
    }

    public function getPublished()
    {
        return $this->article->where('state = ?', 'published');
    }

    public function getPublishedTeasers($teaserLength = 300)
    {
        return $this->article->select(
            'article.id,
            LEFT(article.content, ' . $teaserLength . ') AS content,
            article.headline,
            article.state,
            article.user_id,
            article.created'
        )->where('state = ?', 'published');
    }

    /**
     * @return array
     */
    public function getAllAsIdNamePairs()
    {
        $result = [];
        foreach($this->article->order('created DESC') as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getDiff($colorIds)
    {
        return $this->article->where('id NOT IN (?)', $colorIds);
    }

    /**
     * @param array $values
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insert($values)
    {
        return $this->article->insert($values);
    }

    public function delete()
    {
        return $this->article->delete();
    }

}
