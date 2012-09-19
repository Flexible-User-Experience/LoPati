<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsletterRepository extends EntityRepository
{
    public function getNewsletters()
    {
        $dql = "SELECT n FROM MegapointCmsBundle:Newsletter n ORDER BY n.created DESC";
        
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }
    
    public function getActiveUsers()
    {
        $dql = "SELECT u FROM MegapointCmsBundle:NewsletterUser u WHERE u.active = 1";
        
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }
    
    public function getUsersNotActivated()
    {
        $dql = "SELECT u FROM MegapointCmsBundle:NewsletterUser u WHERE u.active = false AND u.created < :created";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('created', new \DateTime('-5 days'));
        
        return $query->getResult();
    }
}


