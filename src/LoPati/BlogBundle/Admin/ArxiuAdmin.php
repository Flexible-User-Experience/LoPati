<?php


namespace LoPati\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\PageBundle\Model\PageInterface;


use Knp\Menu\ItemInterface as MenuItemInterface;


class ArxiuAdmin extends Admin
{
	

	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('any', 'integer',array('label' => 'Any'))
		->add('actiu','checkbox',array('required'=>FALSE))
		->add('imagePetita', 'file', array('label' => 'Imatge any', 'required'=>false))
		->add('imagePetitaName', 'text', array('label' => 'Nom', 'required' => false, 'read_only'=>true,))
		->add('imagePetita2', 'file', array('label' => 'Imatge any vermell', 'required'=>false))
		->add('imagePetita2Name', 'text', array('label' => 'Nom', 'required' => false, 'read_only'=>true,))
		
		->setHelps(array('any'=>'Ex: 2012'))
		;
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		//->addIdentifier('id')
		->addIdentifier('any')
		->add('imagePetitaName', null, array('label' => 'Imatge any'))
		->add('imagePetita2Name', null, array('label' => 'Imatge any vermell'))
		->add('_action', 'actions', array(
				'actions' => array(
						//'view' => array(),
						'edit' => array(),
						//'delete' => array(),
				), 'label' => 'Accions'));
	}

	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'DESC', // sort direction
			'_sort_by' => 'any' // field name
	);
	
	
}