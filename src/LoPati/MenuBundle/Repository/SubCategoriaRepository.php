<?php

namespace LoPati\MenuBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubCategoriaRepository extends EntityRepository
{
	
	public function findSubCategories($categoria){
	
		$em = $this->getEntityManager();
	
		$consulta2 = $em->createQuery('SELECT sub FROM MenuBundle:SubCategoria sub WHERE
				sub.categoria = :categoria AND sub.actiu = :actiu AND ((sub.link IS NOT null) OR (sub.llista = TRUE))  ORDER BY sub.ordre ASC');
	
	
		$consulta2->setParameter('categoria',$categoria);
		$consulta2->setParameter('actiu','1');
	
		return $consulta2->getResult();
	}
	
	
	
}
