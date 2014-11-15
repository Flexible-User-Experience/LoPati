<?php

namespace LoPati\NewsletterBundle\Controller;

use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Form\NewsletterUserType;
use LoPati\NewsletterBundle\Manager\NewsletterManager;
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
    public function formAction()
    {
        $newsletterUser = new NewsletterUser();
        $form = $this->createForm(new NewsletterUserType(), $newsletterUser);

        return array('form' => $form->createView());
    }

    /**
     * @Route("/newsletter/suscribe", name="newsletter_user")
     * @Method({"POST"})
     */
    public function suscribeAction(Request $request)
    {
        $newsletterUser = new NewsletterUser();
        $form = $this->createForm(new NewsletterUserType(), $newsletterUser);
        $form->handleRequest($request);
        $request->setLocale($this->get('session')->get('_locale'));
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletterUser);
            $em->flush();
            $subject = 'Confirmació per rebre el newsletter de LO PATI';
            if ($newsletterUser->getIdioma() == 'es') {
                $subject = 'Confirmación para recibir el newsletter de LO PATI';
            } else if ($newsletterUser->getIdioma() == 'en') {
                $subject = 'Confirmation to receive newsletter LO PATI';
            }
            /** @var NewsletterManager $nb */
            $nb = $this->container->get('newsletter.build_content');
            $nb->sendMandrilMessage($subject, array($newsletterUser->getEmail()), $this->renderView(
                    'NewsletterBundle:Default:confirmation.html.twig',
                    array(
                        'token' => $newsletterUser->getToken(),
                        'user'  => $newsletterUser,
                    )
                ));
            $flash = $this->get('translator')->trans('suscribe.register');
            $this->get('session')->getFlashBag()->add('notice', $flash);

        } else {
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('suscribe.error'));
        }

        return $this->redirect(
            $this->generateUrl('portada', array('_locale' => $request->getLocale()))
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
        /** @var NewsletterUser $user */
        $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));
        $request->setLocale($this->get('session')->get('_locale'));
        if ($user) {
            $user->setActive(true);
            $em->flush();
            $logger = $this->get('logger');
            $logger->info('user idioma:' . $user->getIdioma());
            $logger->info('get idioma:' . $request->getLocale());
            $logger->info('session idioma:' . $this->get('session')->get('_locale'));
            $subject = 'La seva adreça de correu electrònic ha estat activada correctament';
            if ($user->getIdioma() == 'es') {
                $subject = 'Su dirección de correo electrónico ha sido activada correctamente';
            } else if ($user->getIdioma() == 'en') {
                $subject = 'Your email address has been activated';
            }
            /** @var NewsletterManager $nb */
            $nb = $this->container->get('newsletter.build_content');
            $nb->sendMandrilMessage($subject, array($user->getEmail()), $this->renderView(
                    'NewsletterBundle:Default:activated.html.twig',
                    array(
                        'user' => $user
                    )
                ));
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('suscribe.confirmation.enhorabona')
            );
        }

        return $this->redirect($this->generateUrl('portada', array('_locale' => $request->getLocale())));
    }

    public function visitAction($data, $_locale)
    {
        $host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local' : 'http://lopati.cat';
        $newDate = date('Y-m-d', strtotime($data));
        $em = $this->getDoctrine()->getManager();
        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterByData($newDate);
        $visualitzar_correctament = 'Clica aquí per visualitzar correctament';
        $baixa = 'Clica aquí per donar-te de baixa';
        $lloc = 'Lloc';
        $data = 'Data';
        $links = 'Enllaços';
        $publicat = 'Publicat';
        $organitza = 'Organitza';
        $suport = 'Amb el suport de';
        $follow = 'Segueix-nos a';
        $colabora = 'Col·labora';
        $butlleti = 'Butlletí';
        if ($_locale == 'es') {
            $visualitzar_correctament = 'Pulsa aquí para visualizar correctamente';
            $baixa = 'Pulsa aquí para darte de baja';
            $lloc = 'Lugar';
            $data = 'Fecha';
            $publicat = 'Publicado';
            $links = 'Enlaces';
            $organitza = 'Organiza';
            $suport = 'Con el apoyo de';
            $follow = 'Siguenos en';
            $colabora = 'Colabora';
            $butlleti = 'Boletín';
        } else if ($_locale == 'en') {
            $visualitzar_correctament = 'Click here to visualize correctly';
            $baixa = 'Click here to provide you low';
            $lloc = 'Place';
            $data = 'Date';
            $publicat = 'Published';
            $links = 'Links';
            $organitza = 'Organizes';
            $suport = 'With de support of';
            $follow = 'Follow us';
            $colabora = 'Collaborate';
            $butlleti = 'Newsletter';
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

    public function confirmUnsuscribeAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            if (strlen($email) > 0) {
                $em = $this->getDoctrine()->getManager();
                /** @var NewsletterManager $nb */
                $nb = $this->container->get('newsletter.build_content');
                /** @var NewsletterUser $user */
                $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('email' => $email));
                if ($user) {
                    $edl = array($user->getEmail());
                    $content = $this->get('templating')->render('NewsletterBundle:Default:finalEmailMessage.html.twig', array(
                            'user' => $user,
                        ));
                    $subject = 'Confirmació per NO rebre el newsletter de LO PATI';
                    if ($user->getIdioma() == 'es') {
                        $subject = 'Confirmación para NO recibir el newsletter de LO PATI';
                    } else if ($user->getIdioma() == 'en') {
                        $subject = 'Confirmation to NOT receive newsletter LO PATI';
                    }
                    $result = $nb->sendMandrilMessage($subject, $edl, $content);
                    if ($result[0]['status'] == 'sent' || $result[0]['reject_reason'] == 'test-mode-limit' || $result[0]['status'] == 'queued') {
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            $this->get('translator')->trans('unsuscribe.confirmation.finalemailmessage')
                        );
                    } else {
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            $this->get('translator')->trans('unsuscribe.confirmation.emailsenderror')
                        );
                    }
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        $this->get('translator')->trans('unsuscribe.confirmation.nouser')
                    );
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    $this->get('translator')->trans('unsuscribe.confirmation.noemail')
                );
            }
        }

        return $this->render('NewsletterBundle:Default:confirmUnsuscribe.html.twig');
    }

    /**
     * @Route("/newsletter/confirm-unsuscribe/{token}", name="newsletter_unsuscribe")
     */
    public function unsuscribeAction(Request $request, $token)
    {
        $request->setLocale($this->get('session')->get('_locale'));
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));
        if ($user) {
            $em->remove($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('unsuscribe.confirmation.enhorabona')
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('unsuscribe.confirmation.actionerror')
            );
        }

        return $this->redirect($this->generateUrl('portada', array('_locale' => $request->getLocale())));
    }
}
