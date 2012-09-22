<?php
namespace LoPati\NewsletterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminController extends Controller {
	
	public function enviarAction($id)
	{
		//$object->getId();
		return $id;
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
