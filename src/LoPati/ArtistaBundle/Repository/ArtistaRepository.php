<?php

namespace LoPati\ArtistaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArtistaRepository extends EntityRepository
{
    public function getActiveItemsSortedByPosition()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM ArtistaBundle:Artista a
                            WHERE a.active = 1
                            ORDER BY a.name');
        return $query->getResult();
    }
}
