<?php
namespace LoPati\MenuBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LoPati\MenuBundle\Entity\SubCategoria;
use Symfony\Component\HttpFoundation\Response;

class SubCategories extends AbstractFixture implements OrderedFixtureInterface {
	
	public function getOrder()
	{
		return 2;
	}
	
	public function load(ObjectManager $manager) {


		$subcat1= new SubCategoria();
		$subcat1->setNom('Projecte');
		$subcat1->setOrdre(20);
		$subcat1->setActiu(1);
		$subcat1->setCategoria($manager->merge($this->getReference('cat1')));
		
		$subcat2= new SubCategoria();
		$subcat2->setNom('Presentació');
		$subcat2->setOrdre(10);
		$subcat2->setActiu(1);
		$subcat2->setCategoria($manager->merge($this->getReference('cat1')));
		
		$manager->persist($subcat1);
		$manager->persist($subcat2);
		
		$this->addReference('subcat1', $subcat1);
		$this->addReference('subcat2', $subcat2);
		
		$manager->flush();
		
		
		/*$subcategories = array(
				array('nom' => 'Projecte', 'ordre' => '20', 'actiu' => '1','categoria_id' => '2'),
				array('nom' => 'Presentació', 'ordre' => '10','actiu' => '1','categoria_id' => '1'),
				array('nom' => 'Activitats', 'ordre' => '30','actiu' => '1','categoria' => '1'),
				array('nom' => 'Comunicació', 'ordre' => '40','actiu' => '1','categoria' => '1'),
				array('nom' => 'Residència', 'ordre' => '40','actiu' => '1','categoria' => '1'),
				array('nom' => 'Arxiu', 'ordre' => '40','actiu' => '1','categoria' => '1'),
				);	
		$categoria = $manager->getRepository('MenuBundle:Categoria')->find(8);
		foreach ($subcategories as $subcategoria) {
			$entidad = new SubCategoria();
			$entidad->setNom($subcategoria['nom']);
			$entidad->setOrdre($subcategoria['ordre']);
			$entidad->setActiu($subcategoria['actiu']);
			$entidad->setCategoria($categoria);
			$manager->persist($entidad);

		}
		$manager->flush();*/
	}
}

