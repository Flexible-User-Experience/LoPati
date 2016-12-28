<?php

namespace LoPati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NewsletterPageRepository
 *
 * @package LoPati\NewsletterBundle\Repository
 */
class NewsletterPageRepository extends EntityRepository
{
	public function findPaginesNewsletterById($id) {
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT n,p FROM NewsletterBundle:Newsletter n JOIN n.pagines p WHERE n.id = :id ORDER BY p.data_publicacio DESC');
		$query->setParameter('id', $id);
		
		return $query->getSingleResult();
	}
	
	public function findPaginesNewsletterByData($data) {
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT n,p FROM NewsletterBundle:Newsletter n JOIN n.pagines p  WHERE n.dataNewsletter = :data');
		$query->setParameter('data',$data);
	
		return $query->getSingleResult();
	}

    public function getWaitingNewsletter()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT n FROM NewsletterBundle:Newsletter n WHERE NOT EXISTS (SELECT n2 FROM NewsletterBundle:Newsletter n2 WHERE n2.estat = :sending) AND n.estat = :estat ORDER BY n.id ASC');
        $query->setParameter('estat', 'Waiting');
        $query->setParameter('sending', 'Sending');
        $query->setMaxResults('1');

        return $query->getOneOrNullResult();
    }

    public function getSendingNewsletter()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT n FROM NewsletterBundle:Newsletter n WHERE n.estat = :estat');
        $query->setParameter('estat', 'Sending');

        return $query->getOneOrNullResult();
    }
}
