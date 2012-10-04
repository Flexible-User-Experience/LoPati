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
		->add('fail')
		
		;
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->add('id')
		->addIdentifier('email')
		->add('idioma')
		->add('active',null,array('label'=>'Actiu'))
		->add('token')
		->add('fail')
		;
	}
	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'ASC', // sort direction
			'_sort_by' => 'email' // field name
	);

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('active')
        ;
    }
	
}