<?php
namespace LoPati\NewsletterBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class NewsletterSendCommand extends ContainerAwareCommand
{
	protected function configure()	
	{
		$this
		->setName('newsletter:send')
		->setDefinition(array(
				new InputArgument(
						'max',
						InputArgument::OPTIONAL,
						'Número màxim de correus a enviar',100
				),
				))
		->setDescription('Envia a cada subscrit el newsletter')
				->setHelp(<<<EOT
La comanda <info>newsltter:send</info> envia un email a cada subscrit al newsletter.
EOT
				);
		}

		protected function execute(InputInterface $input, OutputInterface $output)
		{
			$max = $input->getArgument('max');
			
			$contenedor = $this->getContainer();
			$em = $contenedor->get('doctrine')->getEntityManager();
			
			$query = $em->createQuery('SELECT n FROM NewsletterBundle:Newsletter n WHERE
					 NOT EXISTS (SELECT n2 FROM NewsletterBundle:Newsletter n2 WHERE n2.estat = :sending ) AND n.estat = :estat ORDER BY n.id ASC	 ');
			$query->setParameter('estat','Waiting');
			$query->setParameter('sending','Sending');
			$query->setMaxResults('1');
			$newsletter = $query->getOneOrNullResult();
			
			$output->writeln('canvi estat:' .count($newsletter));
			
			if ($newsletter) $newsletter->setEstat('Sending');
			$em->flush();
			
			
			$query = $em->createQuery('SELECT n FROM NewsletterBundle:Newsletter n WHERE n.estat = :estat ');
			$query->setParameter('estat','Sending');
			$query->setMaxResults('1');
			$newsletter2 = $query->getOneOrNullResult();
			
			$query = $em->createQuery('SELECT s FROM NewsletterBundle:NewsletterSend s WHERE s.newsletter = :newsletter ');
			$query->setParameter('newsletter',$newsletter2);
			$query->setMaxResults($max);
			$users = $query->getResult();
			
			$output->writeln('users per enviar:' .count($users));
			
			foreach ($users as $user){
				$output->writeln('mail:'.$user->getUser()->getIdioma());
				
					
			}
			foreach ($newsletter2->getPagines() as $pagina){
				$output->writeln('pagina:'.$pagina->getTitol());
			
					
			}
			$em->flush();
			$output->writeln('Generando emails...');
			// ...
		}
}
	
	
