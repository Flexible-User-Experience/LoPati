<?php
namespace LoPati\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PaginaRepository extends EntityRepository
{
    /*public function findLast3ActiveAndOrderedByDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM ASBAEEventsBundle:News n WHERE n.isActive = TRUE AND n.isEvent = FALSE ORDER BY n.newsDate DESC')
            ->setMaxResults(3)
            ->getResult();
    }*/

   /* public function getActiveItems()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM ASBAEEventsBundle:Training n WHERE n.isActive = 1 ORDER BY n.startDate ASC')
            ->getResult();
    }

    public function getLastItemOrderByStartDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM ASBAEEventsBundle:Training n WHERE n.isActive = 1 ORDER BY n.startDate DESC')
            ->setMaxResults(1)
            ->getResult();
    }
*/
//(n.startDate>=:inici AND n.endDate<=:fi OR n.startDate<:inici AND n.endDate<=:fi AND n.endDate>=:inici OR n.startDate>=:inici AND n.startDate<=:fi AND n.endDate>:fi OR n.startDate<=:inici AND n.endDate>=:fi)
    public function getActiveItemsFromMonthAndYear($mes, $any)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT n FROM BlogBundle:Pagina n WHERE n.actiu = 1 AND
             (n.startDate>=:inici AND n.startDate<=:fi OR n.endDate>=:inici AND n.endDate<=:fi OR n.startDate<=:inici AND n.endDate>=:fi)
             AND n.categoria IS NOT NULL  ORDER BY n.startDate ASC');
        $query->setParameter('inici', date('Y-m-d', mktime(0, 0, 0, $mes, 1, $any)));
        $query->setParameter('fi', date('Y-m-t', mktime(0, 0, 0, $mes, 28, $any)));    // la opcion -t devuelve la cantidad de dias que tiene el mes dado
        return $query->getResult();
    }



    public function getActiveItemsFromNextMonthAndYearForever($mes, $any)
    {
        $mes = $mes + 1;
        if ($mes == 13) {
            $mes = 1; $any++;
        }

        $query = $this->getEntityManager()
            ->createQuery('SELECT n FROM BlogBundle:Pagina n WHERE n.actiu = 1 AND n.startDate >= :inici ORDER BY n.endDate ASC');
        $query->setParameter('inici', date('Y-m-d', mktime(0, 0, 0, $mes, 1, $any)));


        return $query->getResult();
    }

    public function getActiveItemsFromDayAndMonthAndYear($dia,$mes, $any)
    {
        	$newDate = date("Y-m-d", strtotime($any.'-'.$mes.'-'.$dia));
        $query = $this->getEntityManager()
            ->createQuery('SELECT n FROM BlogBundle:Pagina n WHERE n.actiu = 1 AND n.startDate <= :avui AND n.endDate >= :avui ORDER BY n.endDate ASC');
        //$query->setParameter('avui', date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $any)));
        $query->setParameter('avui', $newDate);
           // la opcion -t devuelve la cantidad de dias que tiene el mes dado
        return $query->getResult();
    }

}