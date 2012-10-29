<?php
namespace LoPati\BlogBundle\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;




class BlogChangeCategoriaCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName('blog:change:categoria')
				->setDescription('assigna categoria Arxiu a pagines caducades')
				->setHelp(
						<<<EOT
La comanda <info>blog:change:categoria</info> assigna categoria Arxiu a pagines caducades.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

	;

	
		
		
		$hora=new \DateTime();
	

		
		$contenedor = $this->getContainer();
		$em = $contenedor->get('doctrine')->getEntityManager();

		$query = $em
				->createQuery(
						'SELECT p FROM BlogBundle:Pagina p JOIN p.categoria cat LEFT JOIN p.subCategoria sub  WHERE
					 p.data_caducitat <= :avui AND p.data_caducitat IS NOT NULL and p.actiu = TRUE');
		
		$query->setParameter('avui', new \DateTime('today'));
		
		$pagines = $query->getResult();
		
		$query = $em
		->createQuery(
				'SELECT c FROM MenuBundle:Categoria c WHERE
				c.nom = :cat');
		
		$query->setParameter('cat', 'Arxiu');
		
		$categoria = $query->getSingleResult();
		foreach ($pagines as $pagina) {

		//$output->writeln('canvi estat:' . count($newsletter));


								
								$pagina->setCategoria($categoria);
								$pagina->setSubcategoria(null);
								$em->persist($pagina);
							
		}
		$em->flush();

		//$output->writeln('Generando emails...');
		// ...
	}
}

