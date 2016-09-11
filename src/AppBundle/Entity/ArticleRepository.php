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

}
