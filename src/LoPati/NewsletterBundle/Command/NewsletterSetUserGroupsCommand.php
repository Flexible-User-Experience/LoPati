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
                ->addOption('force', null, InputOption::VALUE_NONE, 'If set, the task will persist records to database')
				->setHelp(
<<<EOT
La comanda <info>newsltter:set:user:groups</info> asigna grup als usuaris. El format del fitxer ha de ser CSV amb camps email i grup.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) 
    {
        if ($input->getOption('force')) {
            $output->writeln('<comment>--force option enabled (this option will persist changes to database)</comment>');
        }
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
                                if ($input->getOption('force')) {
                                    $em->persist($dbGroup);
                                    $em->flush();
                                    $em->clear();
                                }
                            }
                            $dbUser = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('email' => $mail));
                            if ($dbUser) {
                                if ($dbGroup->getUsers()->contains($dbUser)) {

                                } else {
                                    $dbGroup->addUser($dbUser);
                                    if ($input->getOption('force')) {
                                        $em->flush();
                                        $em->clear();
                                    }
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
	}
}
