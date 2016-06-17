<?php

namespace LoPati\MenuBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LoPati\BlogBundle\Entity\Pagina;

class Pagines extends AbstractFixture implements OrderedFixtureInterface 
{
	public function getOrder()
	{
		return 3;
	}

	public function load(ObjectManager $manager) {

	
		$pag1= new Pagina();
		$pag1->setTipus('w');
		$pag1->setTitol('Presentació');
		$pag1->setDescripcio('La programació del centre l’hem organitzat en quatre apartats: exposicions, tallers, convocatòries i miscel·lània. Si resseguiu aquests apartats trobareu ordenades les diferents activitats que programa el centre d’art. Aquesta programació respon a un lògica i uns criteris que parteixen de dos punts inicials: el Pla Director del centre elaborat per Artimetria l’any 2010 i les directrius que marca la consideració de centre d’art pertanyent a la Xarxa de Centre Visuals de Catalunya.
El principis que articulen el programa s’ordenen en cinc línies de treball:
1) Difusió de les arts visuals.
En aquest apartat el centre recull activitats amb llarga trajectòria com la BIAM o el Festival Strobe i engega nous projectes que volen reforçar l’oferta actual. Es pretén ampliar l’atenció cap als creadors emergents i sèniors del territori, posar l’atenció a nous camps específics com l’art urbà o l’art sonor, o temàtiques estretament relacionades amb l’àmbit d’especialització del centre. I per últim, com a institució catalitzadora de l’activitat en arts visuals a la zona, el centre planteja establir un seguit de col·laboracions amb altres iniciatives o institucions de la demarcació a fi de plantejar coproduccions, com ja s’està fent amb el Museu de les Terres de l’Ebre (MTE). A mig termini també hauríem de ser capaços de saltar a l’àmbit internacional que puguin aportar produccions de gran nivell a la programació del centre.

2) Formació, pedagogia i intercanvi de coneixements.
En l’apartat de la formació es plantegen d’una banda tallers i cursos específics enfocats a l’aprofundiment i especificitat en els llenguatges tècnics i expressius, els models de recerca i producció o el desenvolupament conceptual. I, d’altra banda, basades en la filosofia peer to peer, activitats per a l’intercanvi de coneixements entre els propis artistes, tan els residents al centre com els de l’entorn immediat. Finalment el centre, un cop estigui a ple rendiment, també ha de generar un oferta educativa d’activitats per a infants i famílies de manera estable i transversal a totes les activitats que s’hi realitzin.

3) Activitats de recerca i producció a partir de residències d’artistes.
La disponibilitat de la infraestructura ubicada al municipi de Balada permet dissenyar una línia de convocatòries per a residències d’artistes i investigadors que treballin en l’àmbit d’especialització del centre. Qualsevol de les activitats que produeix o acull el centre també pot disposar de la possibilitat d’emprar aquest equipament. Aquest recurs també està compartit amb MTE, que pot facilitar la trobada entre artistes i biòlegs, antropòlegs, arqueòlegs, etc..
4) Documentació, mediateca, arxiu, memòria i fons d’art.
La conservació de la producció i la documentació que genera l’activitat artística requereix d’un arxiu que sigui accessible al públic i consultable per investigadors i artistes. Així, el centre hauria d’assumir la tasca de preservar i catalogar l’actual Fons Municipal d’Art preexistent i les futures adquisicions que es puguin realitzar. En aquests apartats disposem de grans aliats: la Biblioteca S.J. Arbó, l’Arxiu Comarcal i el Museu de les Terres de l’Ebre .

5) Pensament contemporani i les connexions entre art i ciència.
Finalment el centre vol promoure activitats de producció i comunicació del pensament contemporani a través de les arts visuals, i aprofundir en els valors culturals, pedagògics, socioeconòmics o crítics de l’art. La primera acció d’aquesta línia de treball es concretaria en l’edició d’una publicació impresa de caràcter semestral editada conjuntament amb el MTE.');

		$pag1->setActiu(1);
		$pag1->setPortada(0);
		$pag1->setDataPublicacio(new \DateTime('now'));
		$pag1->setCategoria($manager->merge($this->getReference('cat1')));
		$pag1->setSubCategoria($manager->merge($this->getReference('subcat2')));
		
		$manager->persist($pag1);

	
		
		$manager->flush();

		//$this->addReference('cat1', $cat1);

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
