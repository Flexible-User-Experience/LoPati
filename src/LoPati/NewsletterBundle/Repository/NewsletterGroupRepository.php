<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class NewsletterGroupRepository extends EntityRepository
{
    /**
     * Get active items sort by name
     *
     * @return Query
     */
    public function getActiveItemsSortByNameQuery()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT g FROM NewsletterBundle:NewsletterGroup g WHERE g.active = 1 ORDER BY g.name');

        return $query;
    }
}
