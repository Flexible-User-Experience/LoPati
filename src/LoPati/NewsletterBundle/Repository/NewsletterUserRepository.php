<?php

namespace LoPati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use LoPati\NewsletterBundle\Entity\NewsletterGroup;

/**
 * Class NewsletterUserRepository.
 */
class NewsletterUserRepository extends EntityRepository
{
    /**
     * @param int $fails
     *
     * @return array
     */
    public function getActiveUsersWithMoreThanFails($fails)
    {
        $query = $this->getEntityManager()->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.fail >= :fail AND u.active = 1');
        $query->setParameter('fail', $fails);

        return $query->getResult();
    }

    /**
     * Get active users plain array by group.
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
     * Get active users plain array by group & locale.
     *
     * @param NewsletterGroup|null $group
     * @param string $locale
     *
     * @return array
     */
    public function getActiveUsersByGroupAndLocale($group, $locale = 'ca')
    {
        $em = $this->getEntityManager();
        if (!is_null($group)) {
            $query = $em->createQuery('SELECT u, g FROM NewsletterBundle:NewsletterUser u JOIN u.groups g WHERE u.active = 1 AND g.id = :gid AND u.idioma = :locale')->setParameters(array(
                    'gid' => $group->getId(),
                    'locale' => $locale,
                ));
        } else {
            $query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.active = 1 AND u.idioma = :locale')->setParameter('locale', $locale);
        }

        return $query->getArrayResult();
    }

    /**
     * Get active users plain array by group.
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
     * Reusable get active users by group.
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

    /**
     * Get active users by group amount.
     *
     * @param NewsletterGroup|null $group
     *
     * @return int
     */
    public function getActiveUsersByGroupAmount($group)
    {
        $em = $this->getEntityManager();
        if (!is_null($group)) {
            $query = $em->createQuery('SELECT u, g, COUNT(u) AS total FROM NewsletterBundle:NewsletterUser u JOIN u.groups g WHERE u.active = 1 AND g.id = :gid')->setParameter('gid', $group->getId());
        } else {
            $query = $em->createQuery('SELECT u, COUNT(u) AS total FROM NewsletterBundle:NewsletterUser u WHERE u.active = 1');
        }
        $result = $query->getResult();

        return $result[0]['total'];
    }

    /**
     * Get users by email.
     *
     * @param string $email
     * @param int    $limit
     *
     * @return array
     */
    public function getUsersByEmail($email, $limit = 20)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.email LIKE :email ORDER BY u.email ASC')
            ->setParameters(array(
                    'email' => '%'.$email.'%',
                ));

        return $query->getArrayResult();
    }

    /**
     * @return array
     */
    public function findAllEnabled()
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.active = :enabled')
            ->setParameter('enabled', true);

        return $query->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findEnabledWithoutFails()
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.active = :enabled')
            ->andWhere('u.fail = 0')
            ->setParameter('enabled', true);

        return $query->getQuery()->getResult();
    }
}
