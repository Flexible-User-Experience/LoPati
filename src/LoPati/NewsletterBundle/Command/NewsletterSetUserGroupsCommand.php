<?php

namespace LoPati\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterGroup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use LoPati\NewsletterBundle\Entity\NewsletterUser;

class NewsletterSetUserGroupsCommand extends ContainerAwareCommand {

	protected function configure() 
    {
		$this->setName('newsletter:set:user:groups')
				->setDefinition(array(new InputArgument('fitxer', InputArgument::REQUIRED, 'fitxer')))
				->setDescription('Arxiu amb correus i grups')
				->setHelp(
<<<EOT
La comanda <info>newsltter:set:user:groups</info> asigna grup als usuaris. El format del fitxer ha de contenir una adreça de correu electrònic per linea (separador = salt de linea).
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) 
    {	
        $output->writeln('Obrint fitxer de mails....');
		$contenedor = $this->getContainer();
        /* @var EntityManager $em */
        $em = $contenedor->get('doctrine')->getManager();
        $row = 1; $errors = 0; $sets = 0;
		$file = $input->getArgument('fitxer');
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $columns = count($data);
                $output->write($columns . ' fields in line ' . $row . ' ---> ');
                $row++;
                if ($columns == 2) {
                    $mail = $data[0];
                    if (strlen($mail) == 0) {
                        $output->writeln('<error>No mail found</error>');
                        $errors++;
                    } else {
                        $group = $data[1];
                        if (strlen($group) > 0) {
                            $dbGroup = $em->getRepository('NewsletterBundle:NewsletterGroup')->findOneBy(array('name' => $group));
                            if ($dbGroup) {
                                $output->write(' (group ' . $group . ' already exists) ');
                            } else {
                                $output->writeln(' (create new group ' . $group . ') ');
                                $dbGroup = new NewsletterGroup();
                                $dbGroup->setName($group)->setActive(true);
                                $em->persist($dbGroup);
                                $em->flush();
                                $em->clear();
                            }
                            $dbUser = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('email' => $mail));
                            if ($dbUser) {
                                if ($dbGroup->getUsers()->contains($dbUser)) {

                                } else {
                                    $dbGroup->addUser($dbUser);
//                                    $em->persist($dbGroup);
                                    $em->flush();
                                    $em->clear();
                                    $output->writeln($mail . ' · ' . $group);
                                    $sets++;
                                }

                            } else {
                                $output->writeln('<error>No user found inside DB</error>');
                                $errors++;
                            }
                        } else {
                            $output->writeln('<info>No group found</info>');
                        }
                    }
                } else {
                    $errors++;
                }
            }
            fclose($handle);
            $output->writeln($errors . ' errors');
		    $output->writeln($sets . ' mails asignats a grup');
            $output->writeln('TOTAL ' . $row . ' mails avaluats');
        } else {
           $output->writeln('<error>Imposible to open file</error>');
        }





//		$filas = file($max);
//		$handle = fopen($max, 'r');
//		$i = 0;
//		$numero_fila = 0;
//        $z = 0;
//
//		while (!feof($handle)) {
//            $row = fgets($handle, 4096);
//            //$row=stream_get_line($handle, 4096,'\r');
//			// genero array con por medio del separador "," que es el que tiene el archivo txt
//            $sql = str_replace(" ", "", $row);
//            $sql = str_replace(",", "", $sql);
//            $sql = str_replace("\n", "", $sql);
//            $sql = str_replace("\r", "", $sql);
//
//			$query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.email = :mail');
//			$query->setParameter('mail', $sql);
//			$query->setMaxResults('1');
//			$existeix = $query->getResult();
//
//			if (count($existeix) > 0) {
//                $output->writeln("<error>No s'ha pogut afegir el email " . $sql . " perquè ja existeix a la base de dades</error>");
//                $z++;
//			} else {
//                if (strlen($sql)) {
//                    $user = new newsletterUser();
//                    $user->setEmail($sql);
//                    $user->setActive('1');
//                    $user->setIdioma('ca');
//                    $em->persist($user);
//                    $output->writeln("<info>S'ha afegit un registre nou a la base de dades amb el email " . $sql . "</info>");
//                    $i++;
//                    $em->flush();
//                    $em->clear();
//                }
//            }
//            $numero_fila++;
//
//		}
//        $output->writeln($z . ' mails fallats');
//		$output->writeln($i . ' mails guardats');
//        $output->writeln('TOTAL ' . $numero_fila . ' mails avaluats');
	}
}
