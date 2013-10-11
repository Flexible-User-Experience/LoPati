<?php
namespace LoPati\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ConfiguracioDiesLaboralsAgendaRepository extends EntityRepository
{
    public function getActiveWorkingDaysItems()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT n FROM BlogBundle:ConfiguracioDiesLaboralsAgenda n WHERE n.active = 1');
        
        return $query->getResult();
    }
}