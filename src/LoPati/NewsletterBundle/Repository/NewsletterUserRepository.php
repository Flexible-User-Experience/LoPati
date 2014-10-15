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

    /**
     * Get active users plain array by group
     *
     * @param NewsletterGroup|null $group
     *
     * @return array
     */
    public function getActiveUsersPlainArrayByGroup($group)
    {
        return $this->reusableGetActiveUsersByGroup($group)->getArrayResult();
    }

    /**
     * Get active users plain array by group
     *
     * @param NewsletterGroup|null $group
     *
     * @return array
     */
    public function getActiveUsersByGroup($group)
    {
        return $this->reusableGetActiveUsersByGroup($group)->getResult();
    }

    /**
     * Reusable get active users by group
     *
     * @param NewsletterGroup|null $group
     *
     * @return \Doctrine\ORM\AbstractQuery|\Doctrine\ORM\Query
     */
    private function reusableGetActiveUsersByGroup($group)
    {
        $em = $this->getEntityManager();
        if (!is_null($group)) {
            $query = $em->createQuery('SELECT u, g FROM NewsletterBundle:NewsletterUser u JOIN u.groups g WHERE u.active = 1 AND g.id = :gid')->setParameter('gid', $group->getId());
        } else {
            $query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.active = 1');
        }

        return $query;
    }
}
