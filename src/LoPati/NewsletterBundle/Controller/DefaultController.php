<?php

namespace LoPati\NewsletterBundle\Controller;

use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Form\NewsletterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Template()
     */
    public function formAction(Request $request)
    {
        $newsletterUser = new NewsletterUser();

        $form = $this->createForm(new NewsletterUserType(), $newsletterUser);

        return array('form' => $form->createView());
    }

    /**
     * @Route("/newsletter/suscribe", name="newsletter_user")
     * @Template()
     * @Method({"POST"})
     */
    public function suscribeAction(Request $request)
    {
        $newsletterUser = new NewsletterUser();
        $form = $this->createForm(new NewsletterUserType(), $newsletterUser);

        $form->bindRequest($request);

        $request->setLocale($this->get('session')->get('_locale'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletterUser);
            $em->flush();

            // $config = $em->getRepository('MegapointCmsBundle:Configuration')->config();
            $config = 'metalrockero@gmail.com';
            $message = \Swift_Message::newInstance()
                ->setSubject(
                    $this->renderView(
                        'NewsletterBundle:Default:confirmationSubject.html.twig',
                        array('idioma' => $newsletterUser->getIdioma())
                    ),
                    'text/html'
                )
                //->setFrom($config->getEmail())
                ->setFrom('butlleti@lopati.cat')
                ->setTo($newsletterUser->getEmail())
                ->setBody(
                    $this->renderView(
                        'NewsletterBundle:Default:confirmation.html.twig',
                        array(
                            'token' => $newsletterUser->getToken(),
                            'user'  => $newsletterUser
                        )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $flash = $this->get('translator')->trans('suscribe.register');
            $this->get('session')->setFlash('notice', $flash);


            /*  // ...
              $mensaje = \Swift_Message::newInstance()
              ->setSubject('Oferta del día')
              ->setFrom('mailing@cupon.com')
              ->setTo('usuario1@localhost')
              ->setBody('prova')
              ;
              $this->get('mailer')->send($mensaje);*/
        } else {
            $this->get('session')->setFlash('notice', $this->get('translator')->trans('suscribe.error'));
        }

        /*   $request = $this->getRequest();
           $request->setLocale($request->getLocale());*/

        return $this->redirect(
            $this->generateUrl('portada', array('_locale' => $this->get('session')->get('_locale')))
        );
    }

    /**
     * @Route("/newsletter/confirmation/{token}", name="newsletter_confirmation")
     *
     * @Template
     */
    public function confirmationAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));

        $request->setLocale($this->get('session')->get('_locale'));

        if ($user) {
            $user->setActive(true);
            $em->flush();

            //	$request->setLocale($user->getIdioma());


            $logger = $this->get('logger');
            $logger->info('user idioma:' . $user->getIdioma());
            $logger->info('get idioma:' . $request->getLocale());
            $logger->info('session idioma:' . $this->get('session')->get('_locale'));


            $message = \Swift_Message::newInstance()
                ->setSubject(
                    $this->renderView(
                        'NewsletterBundle:Default:confirmationTrueSubject.html.twig',
                        array('idioma' => $user->getIdioma())
                    ),
                    'text/html'
                )
                ->setFrom('butlleti@lopati.cat')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'NewsletterBundle:Default:activated.html.twig',
                        array(
                            'user' => $user
                        )
                    ),
                    'text/html'
                );


            $this->get('mailer')->send($message);
            $this->get('session')->setFlash(
                'notice',
                $this->get('translator')->trans('suscribe.confirmation.enhorabona')
            );
        }
        $culture = $this->get('session')->get('_locale');

        return $this->redirect($this->generateUrl('portada', array('_locale' => $culture)));
    }

    public function visitAction($data, $_locale)
    {
        $host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local'
            : 'http://lopati.cat';
        $newDate = date("Y-m-d", strtotime($data));
        $em = $this->getDoctrine()->getManager();
        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterByData($newDate);
        $visualitzar_correctament = "Clica aquí per visualitzar correctament";

        if ($_locale == 'ca') {
            $visualitzar_correctament = "Clica aquí per visualitzar correctament";
            $baixa = "Clica aquí per donar-te de baixa";
            $lloc = "Lloc";
            $data = "Data";
            $links = "Enllaços";
            $publicat = "Publicat";

            $organitza = "Organitza";
            $suport = "Amb el suport de";
            $follow = "Segueix-nos a";
            $colabora = "Col·labora";
            $butlleti = "Butlletí";
        } else {
            if ($_locale == 'es') {
                $visualitzar_correctament = "Pulsa aquí para visualizar correctamente";
                $baixa = "Pulsa aquí para darte de baja";
                $lloc = "Lugar";
                $data = "Fecha";
                $publicat = "Publicado";
                $links = "Enlaces";

                $organitza = "Organiza";
                $suport = "Con el apoyo de";
                $follow = "Siguenos en";
                $colabora = "Colabora";
                $butlleti = "Boletín";
            } else {
                if ($_locale == 'en') {
                    $visualitzar_correctament = "Click here to visualize correctly";
                    $baixa = "Click here to provide you low";
                    $lloc = "Place";
                    $data = "Date";
                    $publicat = "Published";
                    $links = "Links";

                    $organitza = "Organizes";
                    $suport = "With de support of";
                    $follow = "Follow us";
                    $colabora = "Collaborate";
                    $butlleti = "Newsletter";
                }
            }
        }

        return $this->render(
            'NewsletterBundle:Default:mail.html.twig',
            array(
                'host'                     => $host,
                'pagines'                  => $pagines,
                'idioma'                   => $_locale,
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

    public function confirmUnsuscribeAction(Request $request, $token)
    {
        $request->setLocale($this->get('session')->get('_locale'));

        return $this->render('NewsletterBundle:Default:confirmUnsuscribe.html.twig', array('token' => $token));

    }

    /**
     * @Route("/newsletter/confirm-unsuscribe/{token}", name="newsletter_confirm_unsuscribe")
     *
     * @Template
     */
    public function unsuscribeAction(Request $request, $token)
    {
        $request->setLocale($this->get('session')->get('_locale'));
        //return array('token' => $token);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));

        if ($user) {
            $em->remove($user);
            $em->flush();

            $this->get('session')->setFlash(
                'notice',
                $this->get('translator')->trans('unsuscribe.confirmation.enhorabona')
            );
        }

        return $this->redirect($this->generateUrl('portada', array('_locale' => $request->getLocale())));
    }
}