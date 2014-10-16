<?php

namespace LoPati\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Psr\Log\LoggerInterface;

class NewsletterSendCommand extends ContainerAwareCommand
{
	protected function configure()
    {
		$this->setName('newsletter:send')
				->setDescription('Envia a cada subscrit el newsletter')
				->setHelp(
						<<<EOT
La comanda <info>newsletter:send</info> envia un email a cada subscrit al newsletter.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Router $router */
        $router = $this->getContainer()->get('router');
        /** @var NewsletterManager $nb */
        $nb = $this->getContainer()->get('newsletter.build_content');
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
		$host = $router->getContext()->getScheme() . '://' . $router->getContext()->getHost();

        // Welcome
        $this->makeLog('Welcome to LoPati newsletter:send command.');
        $this->makeLog('initializing... host = ' . $host);
        $dtStart = new \DateTime();

        /** @var Newsletter $newsletter */
		$newsletter = $em->getRepository('NewsletterBundle:Newsletter')->getWaitingNewsletter();
		if ($newsletter) {
            $newsletter->setEstat('Sending');
            $newsletter->setEnviats(0);
            $em->flush();
            $this->makeLog('Total emails to deliver: ' . $newsletter->getSubscrits());
            $enviats = 0;
            $fallats = 0;
            $subject = 'Butlletí nº ' . $newsletter->getNumero();
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroup($newsletter->getGroup());
            /** @var NewsletterUser $user */
            foreach ($users as $user) {
                $to = $user->getEmail(); $edl = array($to);
                $this->makeLog('get ' . $to . '... rendering template... ');
                $content = $this->getContainer()->get('templating')->render('NewsletterBundle:Default:mail.html.twig', $nb->buildNewsletterContentArray($newsletter->getId(), $newsletter, $host, $user->getIdioma(), $user->getToken()));
                $this->makeLog('sending mail... ');
                $result = $nb->sendMandrilMessage($subject, $edl, $content);
                if ($result[0]['status'] == 'sent') {
                    $enviats++;
                    $this->makeLog('done!');
                    $newsletter->setEnviats($newsletter->getEnviats() + 1);
                } else {
                    $fallats++;
                    $user->setFail($user->getFail() + 1);
                    $this->makeLog('error! ' . $result[0]['status'] . ': ' . $result[0]['reject_reason']);
                }
                $em->flush();
            }
            $newsletter->setEstat('Sended');
            $newsletter->setFiEnviament(new \DateTime('now'));
            $em->flush();
            $this->makeLog('Emails delivered: ' . $enviats);
            $this->makeLog('Wrong delivers: ' . $fallats);
        } else {
            // log no delivery
            $this->makeLog('ERROR: Nothing to deliver, no waiting newsletter found.');
        }
        $dtEnd = new \DateTime();
        $this->makeLog('Total ellapsed time: ' . $dtStart->diff($dtEnd)->format('%H:%I:%S'));
	}

    private function makeLog($msg)
    {
        /** @var $logger LoggerInterface */
        $logger = $this->getContainer()->get('logger');
        $logger->info($msg, array('internal-newsletter-command'));
    }
}
