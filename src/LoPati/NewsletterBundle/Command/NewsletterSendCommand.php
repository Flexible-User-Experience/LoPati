<?php

namespace LoPati\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class NewsletterSendCommand extends ContainerAwareCommand
{
	protected function configure()
    {
		$this->setName('newsletter:send')
				->setDefinition(
						array(
								new InputArgument('max',
										InputArgument::OPTIONAL,
										'Número màxim de correus a enviar',
										1000),))
				->setDescription('Envia a cada subscrit el newsletter')
				->setHelp(
						<<<EOT
La comanda <info>newsletter:send</info> envia un email a cada subscrit al newsletter.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var NewsletterManager $nb */
        $nb = $this->getContainer()->get('newsletter.build_content');
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
		$max = $input->getArgument('max');
		$host = 'http://www.lopati.cat';
		$hora = new \DateTime();

        // Welcome
        $output->writeln('<info>Welcome to LoPati newsletter:send command.</info>');
		$output->writeln($hora->format('Y-m-d H:i:s'). ' · Host = ' . $host);
        $dtStart = new \DateTime();

		$newsletter = $em->getRepository('NewsletterBundle:Newsletter')->getWaitingNewsletter();
		if ($newsletter) {
            $newsletter->setEstat('Sending');
            $em->flush();
        }

		$newsletter2 = $em->getRepository('NewsletterBundle:Newsletter')->getSendingNewsletter();
		if ($newsletter2) {
			$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($newsletter2->getId());
			$newsletterSends = $em->getRepository('NewsletterBundle:NewsletterSend')->getItemsByNewsletter($newsletter2, $max);
			
			if ($newsletterSends) {
                $output->writeln('Total emails to deliver: ' . count($newsletterSends)); $output->writeln(' ');
                $enviats = 0;
                $fallats = 0;
                $subject = 'Butlletí nº ' . $newsletter2->getNumero();
                /** @var NewsletterSend $newsletterSend */
                foreach ($newsletterSends as $newsletterSend) {

                    if (!$newsletterSend->getUser() instanceof NewsletterUser) {
                        $em->remove($newsletterSend);
                        $em->flush();
                        continue;
                    }
                    $to = $newsletterSend->getUser()->getEmail();
                    $edl = array($to);
                    $output->write('get ' . $newsletterSend->getUser()->getEmail() . '... rendering template... ');

                    $content = $this->getContainer()->get('templating')->render('NewsletterBundle:Default:mail.html.twig', $nb->buildNewsletterContentArray($newsletter2->getId(), $pagines, $host, $newsletterSend->getUser()->getIdioma(), $newsletterSend->getUser()->getToken()));

                    $output->write('sending mail... ');

                    $result = $nb->sendMandrilMessage($subject, $edl, $content);

                    if ($result[0]['status'] == 'sent') {
                        $enviats++;
                        $output->writeln('done!');
                    } else {
                        $fallats++;
                        $newsletterSend->getUser()->setFail($newsletterSend->getUser()->getFail() + 1);
                        $output->writeln('<error>error! ' . $result[0]['status'] . ': ' . $result[0]['reject_reason'] . '</error>');
                    }
                    $em->remove($newsletterSend);
                    $em->flush();
                }

                $newsletter2->setEnviats($newsletter2->getEnviats() + $enviats);
                $output->writeln(' ');
                $output->writeln('Emails delivered: ' . $enviats);
                $output->writeln('Wrong delivers: ' . $fallats);

			} else {
				//$output->writeln('entra else');
				$newsletter2->setEstat('Sended');
				$newsletter2->setFiEnviament(new \DateTime('now'));
			}
		}
		$em->flush();

        $dtEnd = new \DateTime();
        $output->writeln('Total ellapsed time: ' . $dtStart->diff($dtEnd)->format('%H:%I:%S'));
	}
}
