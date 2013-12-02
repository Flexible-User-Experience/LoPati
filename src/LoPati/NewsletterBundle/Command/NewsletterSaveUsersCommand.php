<?php
namespace LoPati\NewsletterBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use LoPati\NewsletterBundle\Entity\NewsletterUser;

class NewsletterSaveUsersCommand extends ContainerAwareCommand {

	protected function configure() 
    {
		$this->setName('newsletter:save:users')
				->setDefinition(array(new InputArgument('fitxer', InputArgument::REQUIRED, 'fitxer')))
				->setDescription('Arxiu amb correus')
				->setHelp(
<<<EOT
La comanda <info>newsltter:ssave:users</info> importa usuaris a la base de dades. El format del fitxer ha de contenir una adreça de correu electrònic per linea.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) 
    {	
        $output->writeln('Guardant mails....');
		$contenedor = $this->getContainer();
		$em = $contenedor->get('doctrine')->getEntityManager();
		
		$max = $input->getArgument('fitxer');
		$filas = file($max);	
		$handle = fopen($max, 'r');
		$i = 0;
		$numero_fila = 0;
        $z = 0;

		// mientras exista una fila
		while (!feof($handle)) {

            $row = fgets($handle, 4096);
            //$row=stream_get_line($handle, 4096,'\r');
			// genero array con por medio del separador "," que es el que tiene el archivo txt
            $sql = str_replace(" ", "", $row);
            $sql = str_replace(",", "", $sql);
            $sql = str_replace("\n", "", $sql);
            $sql = str_replace("\r", "", $sql);

			$query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.email = :mail');
			$query->setParameter('mail', $sql);
			$query->setMaxResults('1');
			$existeix = $query->getResult();
			
			if (count($existeix) > 0) {
                $output->writeln("<error>No s'ha pogut afegir el email " . $sql . " perquè ja existeix a la base de dades</error>");
                $z++;
			} else {
                if (strlen($sql)) {
                    $user = new newsletterUser();
                    $user->setEmail($sql);
                    $user->setActive('1');
                    $user->setIdioma('ca');
                    $em->persist($user);
                    $output->writeln("<info>S'ha afegit un registre nou a la base de dades amb el email " . $sql . "</info>");
                    $i++;
                    $em->flush();
                }
            }
            $numero_fila++;
			
		}
        $output->writeln($z . ' mails fallats');
		$output->writeln($i . ' mails guardats');
        $output->writeln('TOTAL ' . $numero_fila . ' mails avaluats');
	}
}

