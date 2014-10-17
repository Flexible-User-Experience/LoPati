<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsletterGroupRepository extends EntityRepository
{
    /**
     * Get active items sort by name
     *
     * @return array
     */
    public function getActiveItemsSortByNameQuery()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT g FROM NewsletterBundle:NewsletterGroup g WHERE g.active = 1 ORDER BY g.name');

        return $query;
    }
}
