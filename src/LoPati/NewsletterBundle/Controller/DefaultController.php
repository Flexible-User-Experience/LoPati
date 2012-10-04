<?php

namespace LoPati\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Form\NewsletterUserType;
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
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($newsletterUser);
            $em->flush();
            
           // $config = $em->getRepository('MegapointCmsBundle:Configuration')->config();
           $config='metalrockero@gmail.com';
            $message = \Swift_Message::newInstance()
                    ->setSubject($this->renderView('NewsletterBundle:Default:confirmationSubject.html.twig',
                    		array('idioma'=>$newsletterUser->getIdioma())),'text/html')
                    //->setFrom($config->getEmail())
            		->setFrom('butlleti@lopati.cat')
                    ->setTo($newsletterUser->getEmail())
                    ->setBody($this->renderView('NewsletterBundle:Default:confirmation.html.twig', array(
                        'token' => $newsletterUser->getToken(), 'user'=>$newsletterUser
                    )), 'text/html')
            ;
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
    
        return $this->redirect($this->generateUrl('portada', array('_locale' =>$this->get('session')->get('_locale'))));
    }

    /**
     * @Route("/newsletter/confirmation/{token}", name="newsletter_confirmation")
     * @Template
     */
    public function confirmationAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));
		
        $request->setLocale($this->get('session')->get('_locale'));
		
        if ($user) {
            $user->setActive(true);
            $em->flush();
			
            //	$request->setLocale($user->getIdioma());
         
           
            $logger = $this->get('logger');
            $logger->info('user idioma:'.$user->getIdioma());
            $logger->info('get idioma:'.$request->getLocale());
            $logger->info('session idioma:'.$this->get('session')->get('_locale'));
            
         
            $message = \Swift_Message::newInstance()
                    ->setSubject($this->renderView('NewsletterBundle:Default:confirmationTrueSubject.html.twig',
                    		array('idioma'=>$user->getIdioma())),'text/html')
                    ->setFrom('butlleti@lopati.cat')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView('NewsletterBundle:Default:activated.html.twig', array(
                        'user' => $user
                    )), 'text/html')	
            ;
            
            
         
            $this->get('mailer')->send($message);
            $this->get('session')->setFlash('notice',$this->get('translator')->trans('suscribe.confirmation.enhorabona'));
        }
        $culture=$this->get('session')->get('_locale');
        return $this->redirect($this->generateUrl('portada', array('_locale' => $culture)));
    }
    


	public function visitAction($data,$_locale){
		$host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local'
		: 'http://lopati.cat';
		$newDate = date("Y-m-d", strtotime($data));
		$em = $this->getDoctrine()->getEntityManager();
		$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterByData($newDate);
		return $this->render('NewsletterBundle:Default:mail.html.twig',array('host'=>$host, 'pagines'=>$pagines, 'idioma'=>$_locale));
		
	}

	public function confirmUnsuscribeAction(Request $request,$token)
	{
		$request->setLocale($this->get('session')->get('_locale'));
				return $this->render('NewsletterBundle:Default:confirmUnsuscribe.html.twig',array('token'=>$token));
		
	}
	/**
	 * @Route("/newsletter/confirm-unsuscribe/{token}", name="newsletter_confirm_unsuscribe")
	 * @Template
	 */
	public function unsuscribeAction(Request $request,$token)
	{
		$request->setLocale($this->get('session')->get('_locale'));
		//return array('token' => $token);
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));
		 
		if ($user) {
			$em->remove($user);
			$em->flush();
			 
			$this->get('session')->setFlash('notice', $this->get('translator')->trans('unsuscribe.confirmation.enhorabona'));
		}
		 
		return $this->redirect($this->generateUrl('portada', array('_locale' => $request->getLocale())));
	}
}