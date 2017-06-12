<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\AdminBundle\Entity\EmailToken;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Psr\Log\LoggerInterface;
use SendGrid\Response;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class NewsletterPageAdminController.
 *
 * @category AdminController
 *
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterPageAdminController extends Controller
{
    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enviarAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var NewsletterManager $nb */
        $nb = $this->get('newsletter.build_content');
        /** @var Newsletter $newsletter */
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id); // ensure that pages will be sorted by date, don't use ->find($id) doctine method
        if ($newsletter->getEstat() == null || $newsletter->getEstat() == 'Sended') {
            $newsletter->setEstat('Waiting');
            $newsletter->setIniciEnviament(new \DateTime('now'));
            $newsletter->setFiEnviament(null);

//            $rejects = $nb->getRejectList();
//            foreach ($rejects as $item) {
//                $email = $item['email'];
//                $reason = $item['reason'];
//                /** @var NewsletterUser $ruser */
//                $ruser = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('email' => $email));
//                if ($ruser) {
//                    if ($reason == 'soft-bounce') {
//                        // increment fail counter
//                        $ruser->setFail($ruser->getFail() + 1);
//                        $em->flush();
//                    } else {
//                        // remove user (other API reason results are: hard-bounce, spam, unsub & custom)
//                        $em->remove($ruser);
//                        $em->flush();
//                    }
//                }
//            }

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

            $this->makeLog('Welcome to LoPati newsletter:send command.');
            $this->makeLog('initializing... host = '.$host);
            $dtStart = new \DateTime();

            $newsletter->setEstat('Sending');
            $newsletter->setEnviats(0);
            $em->flush();
            $this->makeLog('Total emails to deliver: '.$newsletter->getSubscrits());
            $enviats = 0;
            $fallats = 0;

            $this->sendEmailBlockToLocale('ca', $newsletter, $em, $nb, $host);
            $this->sendEmailBlockToLocale('es', $newsletter, $em, $nb, $host);
            $this->sendEmailBlockToLocale('en', $newsletter, $em, $nb, $host);

            $newsletter->setEstat('Sended');
            $newsletter->setFiEnviament(new \DateTime('now'));
            $em->flush();
            $this->makeLog('Emails delivered: '.$enviats);
            $this->makeLog('Wrong delivers: '.$fallats);

            $dtEnd = new \DateTime();
            $this->makeLog('Total ellapsed time: '.$dtStart->diff($dtEnd)->format('%H:%I:%S'));
        } else {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', 'Impossible enviar el newsletter núm. '.$newsletter->getNumero());
        }

        return $this->redirect('../list');
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
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
        $subject = '[TEST] '.$newsletter->getName();
        $edl = array(
            new EmailToken($this->getParameter('email_address_test_1'), 'fake-token-1'),
            new EmailToken($this->getParameter('email_address_test_2'), 'fake-token-2'),
            new EmailToken($this->getParameter('email_address_test_3'), 'fake-token-3'),
        );

        /** @var Response $result */
        $result = $this->get('app.mailer.service')->batchDelivery($subject, $edl, $contenido);
        if ($result == true) {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'Mail de test enviat correctament a les bústies: '.$this->getParameter('email_address_test_1').', '.$this->getParameter('email_address_test_2').' i '.$this->getParameter('email_address_test_3')
            );
        } else {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', 'ERROR al enviar el test');
        }

        $newsletter->setTest('1');
        $em->flush();

        return $this->redirect('../list');
    }

    /**
     * @return string
     */
    private function getHostRoute()
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        return $router->getContext()->getScheme().'://'.$router->getContext()->getHost();
    }

    /**
     * @param $msg
     */
    private function makeLog($msg)
    {
        /** @var $logger LoggerInterface */
        $logger = $this->get('logger');
        $logger->debug($msg, array('internal-newsletter-command'));
    }

    /**
     * @param $locale
     * @param Newsletter        $newsletter
     * @param EntityManager     $em
     * @param NewsletterManager $nb
     * @param $host
     */
    private function sendEmailBlockToLocale($locale, Newsletter $newsletter, EntityManager $em, NewsletterManager $nb, $host)
    {
        $this->get('translator')->setLocale($locale);
        /** @var Pagina $pagina */
        foreach ($newsletter->getPagines() as $pagina) {
            $pagina->setLocale($locale);
            $subCategoria = $pagina->getSubCategoria();
            if ($subCategoria) {
                $subCategoria->setlocale($locale);
                $em->refresh($subCategoria);
            }
            $em->refresh($pagina);
        }
        /** @var array $users */
        $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroupAndLocale($newsletter->getGroup(), $locale);
        if (count($users) > 0) {
            $edl = array();
            /** @var NewsletterUser $user */
            foreach ($users as $user) {
                $to = $user['email'];
                $edl[] = new EmailToken($user->getEmail(), $user->getToken());
                array_push($edl, $to);
                $this->makeLog('add '.$to.'... to rendering template <'.$locale.'>... ');
            }
            $content = $this->get('templating')->render('NewsletterBundle:Default:mail2.html.twig', $nb->buildNewsletterContentArray($newsletter->getId(), $newsletter, $host, $locale));
            $this->makeLog('sending mail... ');
            /** @var Response $result */
            $result = $this->get('app.mailer.service')->batchDelivery($newsletter->getName(), $edl, $content);
            if ($result == true) {
                $this->makeLog('done!');
                $newsletter->setEnviats($newsletter->getEnviats() + count($users));
            } else {
                $this->makeLog('error!');
            }
            $em->flush();
        }
    }
}
