<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Psr\Log\LoggerInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Process\Process;

class NewsletterAdminController extends Controller
{
    const testEmail1 = 'direccio@lopati.cat';
    const testEmail2 = 'comunicacio@lopati.cat';
    const testEmail3 = 'david@flux.cat';

    public function enviarAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Newsletter $newsletter */
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->find($id);
        if ($newsletter->getEstat() == null || $newsletter->getEstat() == 'Sended') {
            $newsletter->setEstat('Waiting');
            $newsletter->setIniciEnviament(new \DateTime('now'));
            $newsletter->setFiEnviament(null);
            // Clean fail delivery users
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersWithMoreThanFails(3);
            foreach ($users as $user) {
                $em->remove($user);
            }
            $em->flush();
            // Set total deliveries
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroupAmount($newsletter->getGroup());
            $newsletter->setSubscrits($users);
            $em->flush();

            // Start delivery process asynchronous
//            $command = 'php ' . $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'console newsletter:send --env=' . $this->get('kernel')->getEnvironment();
//            $process = new Process($command);
//            $process->start();
//            $this->get('session')->getFlashBag()->add('sonata_flash_success', 'Iniciant enviament del newsletter núm. ' . $newsletter->getNumero());

            // Welcome
            $host = $this->get('kernel')->getEnvironment() == 'prod' ? 'http://www.lopati.cat' : 'http://lopati2.local';
            /** @var NewsletterManager $nb */
            $nb = $this->get('newsletter.build_content');
            $this->makeLog('Welcome to LoPati newsletter:send command.');
            $this->makeLog('initializing... host = ' . $host);
            $dtStart = new \DateTime();

            $newsletter->setEstat('Sending');
            $newsletter->setEnviats(0);
            $em->flush();
            $this->makeLog('Total emails to deliver: ' . $newsletter->getSubscrits());
            $enviats = 0;
            $fallats = 0;

            $this->sendEmailBlockToLocale('ca', $newsletter, $em, $nb, $host);
            $this->sendEmailBlockToLocale('es', $newsletter, $em, $nb, $host);
            $this->sendEmailBlockToLocale('en', $newsletter, $em, $nb, $host);
            
            $newsletter->setEstat('Sended');
            $newsletter->setFiEnviament(new \DateTime('now'));
            $em->flush();
            $this->makeLog('Emails delivered: ' . $enviats);
            $this->makeLog('Wrong delivers: ' . $fallats);

            $dtEnd = new \DateTime();
            $this->makeLog('Total ellapsed time: ' . $dtStart->diff($dtEnd)->format('%H:%I:%S'));


        } else {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', 'Impossible enviar el newsletter núm. ' . $newsletter->getNumero());
        }

        return $this->redirect('../list');
    }

    public function previewAction($id)
    {
        /** @var NewsletterManager $nb */
        $nb = $this->container->get('newsletter.build_content');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
        $host = $this->getHostRoute();

        return $this->render('AdminBundle:Newsletter:preview.html.twig', $nb->buildNewsletterContentArray($id, $newsletter, $host, 'ca'));
    }

    public function testAction($id)
    {
        /** @var NewsletterManager $nb */
        $nb = $this->container->get('newsletter.build_content');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Newsletter $newsletter */
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->find($id);
        /** @var Newsletter $newsletter2 */
        $newsletter2 = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
        $host = $this->getHostRoute();
        $contenido = $this->renderView('NewsletterBundle:Default:mail2.html.twig', $nb->buildNewsletterContentArray($id, $newsletter2, $host, 'ca'));
        $subject = '[TEST] ' . $newsletter->getName();
        $edl = array(
            self::testEmail1,
            self::testEmail2,
            self::testEmail3,
        );

        $result = $nb->sendMandrilMessage($subject, $edl, $contenido);
        if ($result[0]['status'] == 'sent' || $result[0]['reject_reason'] == 'test-mode-limit') {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'Mail de test enviat correctament a les bústies: ' . self::testEmail1 . ', ' . self::testEmail2 . ' i ' . self::testEmail3
            );
        } else {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', 'ERROR: Status = "' . $result[0]['status'] . '" Reason: "' . $result[0]['reject_reason'] . '"');
        }

        $newsletter->setTest('1');
        $em->flush();

        return $this->redirect('../list');
    }

    private function getHostRoute()
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        return $router->getContext()->getScheme() . '://' . $router->getContext()->getHost();
    }

    private function makeLog($msg)
    {
        /** @var $logger LoggerInterface */
        $logger = $this->get('logger');
        $logger->debug($msg, array('internal-newsletter-command'));
    }
    
    private function sendEmailBlockToLocale($locale, Newsletter $newsletter, EntityManager $em, NewsletterManager $nb, $host)
    {
        $this->get('translator')->setLocale($locale);
        /** @var Pagina $pagina */
        foreach ($newsletter->getPagines() as $pagina){
            $pagina->setLocale($locale);
            $subCategoria = $pagina->getSubCategoria();
            $subCategoria->setlocale($locale);
            $em->refresh($subCategoria);
            $em->refresh($pagina);
        }
        /** @var array $users */
        $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroupAndLocale($newsletter->getGroup(), $locale);
        if (count($users) > 0) {
            $edl = array();
            /** @var NewsletterUser $user */
            foreach ($users as $user) {
                $to = $user['email'];
                array_push($edl, $to);
                $this->makeLog('add ' . $to . '... to rendering template <' . $locale . '>... ');
            }
            //$this->makeLog('get ' . $to . '... rendering template... ');
            $content = $this->get('templating')->render('NewsletterBundle:Default:mail2.html.twig', $nb->buildNewsletterContentArray($newsletter->getId(), $newsletter, $host, $locale));
            $this->makeLog('sending mail... ');
            $result = $nb->sendMandrilMessage($newsletter->getName(), $edl, $content);
            if ($result[0]['status'] == 'sent' || $result[0]['reject_reason'] == 'test-mode-limit' || $result[0]['status'] == 'queued') {
                $this->makeLog('done!');
                $newsletter->setEnviats($newsletter->getEnviats() + count($users));
            } else {
                //$fallats = $fallats + count($users);
                //$user->setFail($user->getFail() + 1);
                $this->makeLog('error! ' . $result[0]['status'] . ': ' . $result[0]['reject_reason']);
            }
            $em->flush();
        }
    }
}
