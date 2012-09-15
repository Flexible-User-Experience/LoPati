<?php


namespace LoPati\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Profile Admin
 */
class ProfileAdmin extends Admin
{

	/**
	 * Configure the list
	 *
	 * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
	 */
	protected function configureListFields(ListMapper $list)
	{
		$list
		->addIdentifier('name')
		->add('description', null, array('label' => 'Description'));
	}

	/**
	 * Configure the form
	 *
	 * @param FormMapper $formMapper formMapper
	 */
	public function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('name')
		->add('description')
		->add('translations', 'translations',array(
				'required'  => false,
                'by_reference' => false,
                'locales' => array('es', 'en'),
				'attr' => array('style' => 'float: left; width: 400px;')
		
            ));
		

	}

}