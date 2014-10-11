<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class NewsletterSendAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		
		->add('id')
		->add('user')
		->add('newsletter');
	}

	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->addIdentifier('id')
		->add('user')
		->add('newsletter')

				;
	}
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
		->add('id')
		->add('user')
		->add('newsletter')
		;
	}
}
