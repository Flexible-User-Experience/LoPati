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
		
		
		$hora=new \DateTime();
		$output->writeln($hora->format('Y-m-d H:i:s'));
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

		//$output->writeln('canvi estat:' . count($newsletter));

		if ($newsletter)
			$newsletter->setEstat('Sending');
		$em->flush();

		$query = $em
				->createQuery(
						'SELECT n FROM NewsletterBundle:Newsletter n WHERE n.estat = :estat ');
		$query->setParameter('estat', 'Sending');

		$newsletter2 = $query->getOneOrNullResult();
		
		if ($newsletter2) {
			//$output->writeln('entra pagines');
			
			$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($newsletter2->getId());
			
		
			
			
			//$this->container->getParameter('cupon.ciudad_por_defecto'),
			//$output->writeln('entra newsletter2');
			$query = $em
					->createQuery(
							'SELECT s FROM NewsletterBundle:NewsletterSend s   WHERE s.newsletter = :newsletter ');
			$query->setParameter('newsletter', $newsletter2);
			$query->setMaxResults($max);
			$users = $query->getResult();
			
			if ($users){
			$output->writeln('users per enviar correu:' . count($users));
			$enviats = 0;
			$fallats = 0;
			$idioma="ca";
			//$output->writeln(count($users));
			foreach ($users as $user) {
				$output->writeln('llista correus a enviar');
				$to=$user->getUser()->getEmail();
				$output->write(' .. ' . $to .' .. ');
				
			}
			foreach ($users as $user) {
				$output->writeln('entra usuari ' .$user->getUser()->getEmail() );
				$visualitzar_correctament="Clica aquí per visualitzar correctament";
				$baixa="Clica aquí per donar-te de baixa";
				$lloc="Lloc";
				$data="Data";
				$links="Enllaços";
				$publicat="Publicat";
				
				$organitza="Organitza";
				$suport="Amb el suport de";
				$follow="Segueix-nos a";
				$colabora="Col·labora";
				$butlleti="Butlletí";
				
				$output->writeln('if es');
				if ($user->getUser()->getIdioma()=='es'){
					$output->writeln('entra es');
						//$output->writeln('entra castella');
					
					$visualitzar_correctament="Pulsa aquí para visualizar correctamente";
					$baixa="Pulsa aquí para darte de baja";
					$lloc="Lugar";
					$data="Fecha";
					$publicat="Publicado";
					$links="Enlaces";
					
					$organitza="Organiza";
					$suport="Con el apoyo de";
					$follow="Siguenos en";
					$colabora="Colabora";
					$butlleti="Boletín";
			
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
					$output->writeln('entra en');
					//$output->writeln('entra ingles');
					
					$visualitzar_correctament="Click here to visualize correctly";
					$baixa="Click here to provide you low";
					$lloc="Place";
					$data="Date";
					$publicat="Published";
					$links="Links";
					
					$organitza="Organizes";
					$suport="With de support of";
					$follow="Follow us";
					$colabora="Collaborate";
					$butlleti="Newsletter";
					
								foreach ($pagines->getPagines() as $pagina){
								
									$pagina->setLocale('en');
									$subCategoria=$pagina->getSubCategoria();
									$subCategoria->setlocale('en');
									//	$pagina->setSubCategoria($pagina->getSubCategoria())->setLocale('es');
									$em->refresh($subCategoria);
									$em->refresh($pagina);
								
								}
					
					$idioma="en";
					
					
					/*
					$pagines->setLocale('en');
					$em->refresh($pagines);*/
				}
				//$output->writeln('mail:' . $user->getUser()->getIdioma());
				//$this->getContainer()->get('templating')->render(...);
				$output->writeln('nem a renderitzar mail.html.twig');
				$contenido = $contenedor->get('templating')->render('NewsletterBundle:Default:mail.html.twig',
				 array('host'=>$host,'pagines'=>$pagines, 'idioma'=>$idioma, 'token'=>$user->getUser()->getToken(),
				 		'visualitzar_correctament' => $visualitzar_correctament,
				 		 'baixa'=>$baixa, 'lloc'=>$lloc, 'data'=>$data, 'publicat'=>$publicat,
				 		'links'=>$links, 'organitza'=>$organitza, 'suport'=>$suport, 'follow'=>$follow,
				 		'colabora'=>$colabora,'butlleti'=>$butlleti));
				$output->writeln('hem renderitzat');
				$to=$user->getUser()->getEmail();
				$config = 'metalrockero@gmail.com';
				$message = \Swift_Message::newInstance()
						//->setSubject('Lo Pati - Newsletter ' . $newsletter2->getDataNewsletter()->format('d-m-Y'))
						//->setFrom($config->getEmail())
				->setSubject('Butlletí nº ' .$newsletter2->getNumero())
				->setFrom(array("butlleti@lopati.cat" => "Centre d'Art Lo Pati" ))
						//->setFrom('butlleti@lopati.cat')
				->setBody($contenido,'text/html');
				
						//->setTo($user->getUser()->getEmail()) 
						/*->setBody($contenedor->get('twig')->render
													          ('NewsletterBundle:Default:confirmation.html.twig', array(
													          
													  )), 'text/html'	)*/

						
		
						try {
							$message->setTo($to);
							$output->write('enviant a' . $to .'.. ');
							$num = $contenedor->get('mailer')->send($message);
						} catch (Swift_TransportException  $e) {
							//Error handled here
						} catch (Swift_MimeException   $e) {
							//Error handled here
						}				
						

				
				if ($num) {

					$enviats++;
					$output->write('enviat ');

				} else {
					$fallats++;
					$user->getUser()->setFail($user->getUser()->getFail() + 1);
				}
				$em->remove($user);
				//$output->writeln('shan enviat:' . $num);
				$em->flush();
			}
			$newsletter2->setEnviats($newsletter2->getEnviats() + $enviats);
			$output->writeln(' ');
			$output->writeln('shan enviat: ' . $enviats . ' mails correctament');
			$output->writeln('han fallat: ' . $fallats . ' mails');
			}else{
				//$output->writeln('entra else');
				$newsletter2->setEstat('Sended');
				$newsletter2->setFiEnviament(new \DateTime('now'));
			}
		}
		$em->flush();

		//$output->writeln('Generando emails...');
		// ...
	}
}

