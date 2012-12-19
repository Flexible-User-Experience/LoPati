<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsletterRepository extends EntityRepository
{
	public function findPaginesNewsletterById($id){
		
		
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT n,p,sub FROM NewsletterBundle:Newsletter n JOIN n.pagines p
				 WHERE n.id = :id ORDER BY p.data_publicacio DESC');
		$query->setParameter('id',$id);
		
		return $query->getSingleResult();
	}
	
	public function findPaginesNewsletterByData($data){
	
	
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT n,p,sub FROM NewsletterBundle:Newsletter n JOIN n.pagines p  WHERE n.dataNewsletter = :data ');
		$query->setParameter('data',$data);
	
		return $query->getSingleResult();
	}
}


