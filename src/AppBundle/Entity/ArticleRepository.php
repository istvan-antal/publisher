<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository {

    public function getCountsByState() {
        $result = [];
        $states = $this->
            createQueryBuilder('c')->
            select('c.state as state', 'count(c.state) as rowCount')->
            addGroupBy('c.state')->
            getQuery()->
            getResult();

        foreach ($states as $state) {
            $result[$state['state']] = intval($state['rowCount']);
        }

        return $result;
    }

    public function findPublishedPostsForSite(Site $site) {
        return $this->findBy([
            'site' => $site,
            'type' => Article::TYPE_POST,
            'state' => Article::STATE_PUBLISHED ],
            ['created' => 'desc']
        );
    }
    
    public function findPublishedPagesForSite(Site $site) {
        return $this->findBy([
            'site' => $site,
            'type' => Article::TYPE_PAGE,
            'state' => Article::STATE_PUBLISHED ],
            ['created' => 'desc']
        );
    }

}
