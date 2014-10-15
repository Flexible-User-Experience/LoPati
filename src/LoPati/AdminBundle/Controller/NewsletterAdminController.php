<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class NewsletterAdminController extends Controller
{
    public function enviarAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Newsletter $newsletter */
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));

        if ($newsletter->getEstat() == null) {
            $newsletter->setEstat('Waiting');
            $newsletter->setIniciEnviament(new \DateTime('now'));
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersWithMoreThanFails(3);
            foreach ($users as $user) {
                $em->remove($user);
            }
            $em->flush();
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersPlainArrayByGroup($newsletter->getGroup());
            foreach ($users as $user) {
                $newsletterSend = new NewsletterSend();
                $newsletterSend->setUser($user);
                $newsletterSend->setNewsletter($newsletter);
                $em->persist($newsletterSend);
            }
            $newsletter->setSubscrits(count($users));
            $em->flush();
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
        $contenido = $this->renderView('NewsletterBundle:Default:mail.html.twig', $nb->buildNewsletterContentArray($id, $newsletter2, $host, 'ca'));
        $subject = '[TEST] Butlletí nº ' . $newsletter->getNumero();
        $edl = array(
            $this->container->getParameter('newsleterEmailDestination1'),
            $this->container->getParameter('newsleterEmailDestination2'),
            $this->container->getParameter('newsleterEmailDestination3'),
        );

        $result = $nb->sendMandrilMessage($subject, $edl, $contenido);

        $this->get('session')->getFlashBag()->add(
            'sonata_flash_success',
            'Mail de test enviat correctament a les bústies: ' . $this->container->getParameter('newsleterEmailDestination1') .
            ', ' . $this->container->getParameter('newsleterEmailDestination2') .
            ' i ' . $this->container->getParameter('newsleterEmailDestination3')
        );

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
}
