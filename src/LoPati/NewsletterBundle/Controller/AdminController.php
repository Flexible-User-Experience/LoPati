<?php
namespace LoPati\NewsletterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Route\RouteCollection;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AdminController extends Controller {
	
	public function enviarAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
	
	
		$news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
		
		if ($news->getEstat() == null){
		
			$news->setEstat('Waiting');
			$news->setIniciEnviament(new \DateTime('now'));
			
			$query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.fail >= :fail AND u.active = :actiu ');
			$query->setParameter('fail','4');
			$query->setParameter('actiu','1');
			
			$users = $query->getResult();
			
			foreach ($users as $user){
					
				$em->remove($user);
					
			}
			
			$em->flush();
			
			$query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.active = :actiu ');
			$query->setParameter('actiu','1');		
			$users = $query->getResult();
			
			foreach ($users as $user){
				
				$newsletterSend = new NewsletterSend();
				$newsletterSend->setUser($user);
				$newsletterSend->setNewsletter($news);
				$em->persist($newsletterSend);
					
			}
			
			
			$news->setSubscrits(count($users));			
			$em->flush();
		}
		
	
	
		
		
		
		return $this->redirect('../list');
	}
	
	public function previewAction($id)
	{
		
		$em = $this->getDoctrine()->getEntityManager();
		$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
		

		
		$host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local'
		: 'http://lopati.cat';
		//$object->getId();
				return $this->render('NewsletterBundle:Admin:preview.html.twig',array('id'=>$id, 'host'=>$host, 'pagines'=>$pagines, 'idioma'=>'ca'));
		
	}
	
	public function testAction($id)
	{
		$userName = $this->container->get('security.context')
		->getToken()
		->getUser()
		->getUsername();
		$user = $this->getDoctrine()
		->getRepository('ApplicationSonataUserBundle:User')
		->findOneByUsername($userName);
		
		$em = $this->getDoctrine()->getEntityManager();
		$newsletter2 = $em->getRepository('NewsletterBundle:Newsletter')->find($id);
		
		$pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
		
		$host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local'
		: 'http://lopati.cat';
		

		$contenido = $this->render('NewsletterBundle:Default:mail.html.twig', array('host'=>$host,'pagines'=>$pagines, 'idioma'=>'ca'));
		
		
		$message = \Swift_Message::newInstance()
		->setSubject('Lo Pati - Newsletter ' . $newsletter2->getDataNewsletter()->format('d-m-Y'))
		//->setFrom($config->getEmail())
		->setFrom('butlleti@lopati.cat')
		->setTo($user->getEmail())
		->setBody($contenido,'text/html')
		;
		$this->get('mailer')->send($message);
		
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
		$news->setTest('1');
		$em->flush();
		
		return $this->redirect('../list');
		
		
	
	}
}
