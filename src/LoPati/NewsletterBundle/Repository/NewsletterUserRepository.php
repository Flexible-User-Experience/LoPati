<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use LoPati\NewsletterBundle\Entity\NewsletterGroup;

class NewsletterUserRepository extends EntityRepository
{
    public function getActiveUsersWithMoreThanFails($fails)
    {
        $query = $this->getEntityManager()->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.fail >= :fail AND u.active = 1');
        $query->setParameter('fail', $fails);

        return $query->getResult();
    }

    public function getActiveUsersPlainArrayByGroup(NewsletterGroup $group)
    {
        $em = $this->getEntityManager();
        if ($group) {
            $query = $em->createQuery('SELECT u, g FROM NewsletterBundle:NewsletterUser u JOIN u.groups g WHERE u.active = 1 AND g.id = :gid')->setParameter('gid', $group->getId());
        } else {
            $query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.active = 1');
        }

        return $query->getArrayResult();
    }
}
