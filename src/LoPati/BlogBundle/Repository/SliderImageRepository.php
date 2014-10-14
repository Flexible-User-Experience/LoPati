<?php

namespace LoPati\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SliderImageRepository extends EntityRepository
{
    public function getActiveSlidesSortByPosition()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s FROM BlogBundle:SliderImage s WHERE s.active = 1 ORDER BY s.position ASC');

        return $query->getResult();
    }
}
