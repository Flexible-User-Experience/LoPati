<?php
namespace LoPati\NewsletterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Route\RouteCollection;
use LoPati\NewsletterBundle\Entity\NewsletterSend;

class AdminController extends Controller {
	
	public function enviarAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
	
	
		$news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
		
		if ($news->getEstat() == null){
		
			$news->setEstat('Waiting');
			
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
		//$object->getId();
				return $this->render('NewsletterBundle:Admin:preview.html.twig',array('id'=>$id));
		
	}
	
	public function testAction($id)
	{
		//$object->getId();
		//<a href="{{ admin.generateObjectUrl('enviar', object) }}">
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
		$news->setTest('1');
		$em->flush();
		
		return $this->redirect('../list');
		
		////$router->generate( 'slug_route_name', array( 'slug' => $sent->getSlug() ), true );
		
	
	}
}
