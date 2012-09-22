<?php
namespace LoPati\NewsletterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterAdmin extends Admin
{
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('name')
		->add('text')
		->add('pagines')

		
		;
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
	->addIdentifier('id')
	->add('test')
	->add('_action', 'actions', array(
			'actions' => array(
					'preview' => array(
							'template' => 'NewsletterBundle:Admin:previewLink.html.twig'),
					'test' => array(
							'template' => 'NewsletterBundle:Admin:testLink.html.twig'),
					'enviar' => array(
							'template' => 'NewsletterBundle:Admin:enviar.html.twig'),
					
	)))		
		;
	}
	protected function configureRoutes(RouteCollection $collection) {
		$collection->add('enviar',
				$this->getRouterIdParameter().'/enviar');
				$collection->add('preview',
						$this->getRouterIdParameter().'/preview');
				$collection->add('test',
						$this->getRouterIdParameter().'/test')
				
						;
	}
}
