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
		
		
		;
	}
	
}
