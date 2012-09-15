<?php
namespace LoPati\MenuBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LoPati\MenuBundle\Entity\Categoria;

class Categories extends AbstractFixture implements OrderedFixtureInterface {
	public function getOrder()
	{
		return 1;
	}
	
	public function load(ObjectManager $manager) {

	/*	$categories = array(
				array('nom' => 'Informació', 'ordre' => '10', 'actiu' => '1'),
				array('nom' => 'Programació', 'ordre' => '20','actiu' => '1'),
				array('nom' => 'Activitats', 'ordre' => '30','actiu' => '1'),
				array('nom' => 'Comunicació', 'ordre' => '40','actiu' => '1'),
				array('nom' => 'Residència', 'ordre' => '40','actiu' => '1'),
				array('nom' => 'Arxiu', 'ordre' => '40','actiu' => '1'),
				);
				*/
		$cat1= new Categoria();
		$cat1->setNom('Informació');
		$cat1->setOrdre(10);
		$cat1->setActiu(1);
		

		
		$manager->persist($cat1);
		
		$manager->flush();
		
		$this->addReference('cat1', $cat1);
		
	/*	foreach ($categories as $categoria) {
			$entidad = new Categoria();
			$entidad->setNom($categoria['nom']);
			$entidad->setOrdre($categoria['ordre']);
			$entidad->setActiu($categoria['actiu']);
			$manager->persist($entidad);
			
			$this->addReference('categoria', $entidad);

		}
		$manager->flush();*/
	}
}
