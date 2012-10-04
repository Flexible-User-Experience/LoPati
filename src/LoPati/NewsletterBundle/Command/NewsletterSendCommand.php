<?php
namespace LoPati\NewsletterBundle\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class NewsletterSendCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName('newsletter:send')
				->setDefinition(
						array(
								new InputArgument('max',
										InputArgument::OPTIONAL,
										'Número màxim de correus a enviar',
										100),))
				->setDescription('Envia a cada subscrit el newsletter')
				->setHelp(
						<<<EOT
La comanda <info>newsltter:send</info> envia un email a cada subscrit al newsletter.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$max = $input->getArgument('max');

		$host = 'dev' == $input->getOption('env') ? 'http://lopati.local'
				: 'http://lopati.cat';

		$output->writeln($host);

		$contenedor = $this->getContainer();
		$em = $contenedor->get('doctrine')->getEntityManager();

		$query = $em
				->createQuery(
						'SELECT n FROM NewsletterBundle:Newsletter n  WHERE
					 NOT EXISTS (SELECT n2 FROM NewsletterBundle:Newsletter n2 WHERE n2.estat = :sending ) AND n.estat = :estat ORDER BY n.id ASC	 ');
		$query->setParameter('estat', 'Waiting');
		$query->setParameter('sending', 'Sending');
		$query->setMaxResults('1');
		$newsletter = $query->getOneOrNullResult();

		$output->writeln('canvi estat:' . count($newsletter));

		if ($newsletter)
			$newsletter->setEstat('Sending');
		$em->flush();

		$query = $em
				->createQuery(
						'SELECT n FROM NewsletterBundle:Newsletter n WHERE n.estat = :estat ');
		$query->setParameter('estat', 'Sending');

		$newsletter2 = $query->getOneOrNullResult();
		
		if ($newsletter2) {
			$output->writeln('entra pagines');
			
			$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($newsletter2->getId());
			
		
			
			
			//$this->container->getParameter('cupon.ciudad_por_defecto'),
			$output->writeln('entra newsletter2');
			$query = $em
					->createQuery(
							'SELECT s FROM NewsletterBundle:NewsletterSend s   WHERE s.newsletter = :newsletter ');
			$query->setParameter('newsletter', $newsletter2);
			$query->setMaxResults($max);
			$users = $query->getResult();
			
			if ($users){
			$output->writeln('users per enviar:' . count($users));
			$enviats = 0;
			$idioma="ca";
			foreach ($users as $user) {
				
				if ($user->getUser()->getIdioma()=='es'){
					
					$output->writeln('entra castella');
			
							foreach ($pagines->getPagines() as $pagina){
								
								$pagina->setLocale('es');
								$subCategoria=$pagina->getSubCategoria();
								$subCategoria->setlocale('es');
							//	$pagina->setSubCategoria($pagina->getSubCategoria())->setLocale('es');
								$em->refresh($subCategoria);
								$em->refresh($pagina);
								
							}
					
					
								$idioma="es";
					
				}elseif ($user->getUser()->getIdioma()=='en'){
					$idioma="en";
					
					$output->writeln('entra ingles');
					/*
					$pagines->setLocale('en');
					$em->refresh($pagines);*/
				}
				$output->writeln('mail:' . $user->getUser()->getIdioma());
				$contenido = $contenedor->get('twig')->render('NewsletterBundle:Default:mail.html.twig',
				 array('host'=>$host,'pagines'=>$pagines, 'idioma'=>$idioma, 'token'=>$user->getUser()->getToken()));
				
				$config = 'metalrockero@gmail.com';
				$message = \Swift_Message::newInstance()
						->setSubject(
								'Lo Pati - Newsletter ' . $newsletter2->getDataNewsletter()->format('d-m-Y'))
						//->setFrom($config->getEmail())
						->setFrom('no-reply@lopati.cat')
						->setTo($user->getUser()->getEmail()) /*->setBody($contenedor->get('twig')->render
													          ('NewsletterBundle:Default:confirmation.html.twig', array(
													          
													  )), 'text/html'	)*/
						->setBody($contenido,'text/html');
				$num = $contenedor->get('mailer')->send($message);

				if ($num) {

					$enviats++;

				} else {

					$user->getUser()->setFail($user->getUser()->getFail() + 1);
				}
				$em->remove($user);
				$output->writeln('shan enviat:' . $num);

			}
			$newsletter2->setEnviats($newsletter2->getEnviats() + $enviats);
			}else{
				$output->writeln('entra else');
				$newsletter2->setEstat('Sended');
				$newsletter2->setFiEnviament(new \DateTime('now'));
			}
		}
		$em->flush();

		$output->writeln('Generando emails...');
		// ...
	}
}
