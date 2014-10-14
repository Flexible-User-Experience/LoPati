<?php
namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;

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
        $em = $this->getDoctrine()->getManager();

        $visualitzar_correctament = "Clica aquí per visualitzar correctament";
        $baixa = "Clica aquí per donar-te de baixa";
        $lloc = "Lloc";
        $data = "Data";
        $publicat = "Publicat";
        $links = "Enllaços";
        $organitza = "Organitza";
        $suport = "Amb el suport de";
        $follow = "Segueix-nos a";
        $colabora = "Col·labora";
        $butlleti = "Butlletí";

        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
        $host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local' : 'http://lopati.cat';

        return $this->render(
            'AdminBundle:Newsletter:preview.html.twig',
            array(
                'id'                       => $id,
                'host'                     => $host,
                'pagines'                  => $pagines,
                'idioma'                   => 'ca',
                'visualitzar_correctament' => $visualitzar_correctament,
                'baixa'                    => $baixa,
                'lloc'                     => $lloc,
                'data'                     => $data,
                'publicat'                 => $publicat,
                'links'                    => $links,
                'organitza'                => $organitza,
                'suport'                   => $suport,
                'follow'                   => $follow,
                'colabora'                 => $colabora,
                'butlleti'                 => $butlleti
            )
        );
    }

    public function testAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dispatcher = $this->get('hip_mandrill.dispatcher');
        $message = new Message();

        $visualitzar_correctament = "Clica aquí per visualitzar correctament";
        $baixa = "Clica aquí per donar-te de baixa";
        $lloc = "Lloc";
        $data = "Data";
        $publicat = "Publicat";
        $links = "Enllaços";
        $organitza = "Organitza";
        $suport = "Amb el suport de";
        $follow = "Segueix-nos a";
        $colabora = "Col·labora";
        $butlleti = "Butlletí";

        /** @var Newsletter $newsletter */
        $newsletter = $em->getRepository('NewsletterBundle:Newsletter')->find($id);
        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
        $host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local' : 'http://lopati.cat';

        $contenido = $this->renderView(
            'NewsletterBundle:Default:mail.html.twig',
            array(
                'host'                     => $host,
                'pagines'                  => $pagines,
                'idioma'                   => 'ca',
                'visualitzar_correctament' => $visualitzar_correctament,
                'baixa'                    => $baixa,
                'lloc'                     => $lloc,
                'data'                     => $data,
                'publicat'                 => $publicat,
                'links'                    => $links,
                'organitza'                => $organitza,
                'suport'                   => $suport,
                'follow'                   => $follow,
                'colabora'                 => $colabora,
                'butlleti'                 => $butlleti
            )
        );

        $message
            ->setSubject('[TEST] Butlletí nº ' . $newsletter->getNumero())
            ->setFromName('Centre d\'Art Lo Pati')
            ->setFromEmail('butlleti@lopati.cat')
            ->addTo($this->container->getParameter('newsleterEmailDestination1'))
            ->addTo($this->container->getParameter('newsleterEmailDestination2'))
            ->addTo($this->container->getParameter('newsleterEmailDestination3'))
            ->setTrackClicks(true)
            ->setHtml($contenido)
        ;
        $dispatcher->send($message);

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
}
