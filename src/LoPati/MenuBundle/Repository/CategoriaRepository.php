<?php
namespace LoPati\MenuBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoriaRepository extends EntityRepository
{
	public function findCategories(){
		
		$em = $this->getEntityManager();
		
		$consulta = $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat   WHERE
				(EXISTS (SELECT sub FROM MenuBundle:SubCategoria sub WHERE sub.categoria = cat.id AND ((sub.link IS NOT NULL) OR (sub.llista = 1)) AND sub.actiu = TRUE)
				OR (cat.link IS NOT NULL) OR (cat.arxiu = 1)) AND cat.actiu = TRUE ORDER BY cat.ordre');
		
		return $consulta->getResult();
	}
	
	

	
}