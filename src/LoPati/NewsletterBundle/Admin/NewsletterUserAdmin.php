<?php
namespace LoPati\NewsletterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterUserAdmin extends Admin
{
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
	
		->add('email')
		->add('idioma', 'choice', array( 'choices'   => array( 'ca' => 'Català','es' => 'Castellano','en'=>'English'), 'required'  => true,))
		->add('active',null,array('label'=>'Actiu','required'=>false))
		
		;
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->addIdentifier('email')
		->add('idioma')
		->add('active',null,array('label'=>'Actiu'))
		
		
		;
	}
	
}