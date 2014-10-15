<?php

namespace LoPati\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;

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
La comanda <info>newsltter:send</info> envia un email a cada subscrit al newsletter.
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

		$output->writeln($hora->format('Y-m-d H:i:s'). ' · Host = ' . $host);

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
                $output->writeln('users per enviar correu: ' . count($newsletterSends));
                $enviats = 0;
                $fallats = 0;
                /** @var NewsletterSend $newsletterSend */
                foreach ($newsletterSends as $newsletterSend) {
                    $output->writeln('llista correus a enviar');
                    if (!$newsletterSend->getUser() instanceof NewsletterUser) {
                        $em->remove($newsletterSend);
                        $em->flush();
                        continue;
                    }
                    $to = $newsletterSend->getUser()->getEmail();
                    $output->write(' .. ' . $to .' .. ');
                    $output->writeln('entra usuari ' . $newsletterSend->getUser()->getEmail());
                    $output->writeln('renderitza template mail.html.twig');

                    $content = $this->getContainer()->get('templating')->render('NewsletterBundle:Default:mail.html.twig', $nb->buildNewsletterContentArray($newsletter2->getId(), $pagines, $host, $newsletterSend->getUser()->getIdioma(), $newsletterSend->getUser()->getToken()));

                    $output->writeln('render finalitzat');

                    $subject = 'Butlletí nº ' . $newsletter->getNumero();
                    $edl = array($to);

                    $result = $nb->sendMandrilMessage($subject, $edl, $content);
                    $num = 0;

//                    try {
//                        $message->setTo($to);
//                        $output->write('enviant a' . $to .'.. ');
//                        $num = $this->getContainer()->get('mailer')->send($message);
//
//                    } catch (\Swift_TransportException $e) {
//                        $output->writeln(' ');
//                        $output->writeln('ha fallat:' . $to);
//
//                    } catch (\Swift_MimeException $e) {
//                        //Error handled here
//                        $output->writeln(' ');
//                        $output->writeln('ha fallat:' . $to);
//
//                    } catch (\Swift_RfcComplianceException $e) {
//                        //Error handled here
//                        $output->writeln(' ');
//                        $output->writeln('ha fallat:' . $to);
//                    }

                    if ($num) {
                        $enviats++;
                        $output->write('enviat ');
                    } else {
                        $fallats++;
                        $newsletterSend->getUser()->setFail($newsletterSend->getUser()->getFail() + 1);
                    }
                    $em->remove($newsletterSend);
                    $em->flush();
                }

                $newsletter2->setEnviats($newsletter2->getEnviats() + $enviats);
                $output->writeln(' ');
                $output->writeln('shan enviat: ' . $enviats . ' mails correctament');
                $output->writeln('han fallat: ' . $fallats . ' mails');

			} else {
				//$output->writeln('entra else');
				$newsletter2->setEstat('Sended');
				$newsletter2->setFiEnviament(new \DateTime('now'));
			}
		}
		$em->flush();
	}
}
