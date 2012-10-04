<?php
namespace LoPati\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Sonata\AdminBundle\Route\RouteCollection;

class CategoriaAdmin extends Admin
{
	
	protected function configureRoutes(RouteCollection $collection)
	{
	
	}
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		//->add('id')
		->add('nom', null, array('label' => 'Nom'))
		->add('ordre', null, array('label' => 'Ordre'))
		->add('actiu', null, array('label' => 'Actiu'))
		->add('arxiu', 'checkbox', array('label' => 'És Arxiu ?' ,'required'  => false))
		->add('link', null, array('label' => 'Pàgina vinculada', 'required'  => false))
		->with('Traduccions')
		->add('translations', 'a2lix_translations',array(
				'label' => ' ',
				'by_reference' => false,
				'required' => false,
				'fields' => array(                          // [Optionnal] Fields configurations. If not, auto detection from translatable annotations
						'nom' => array(
								'label' => 'Nom',                   // Custom label
								'attr' => array(
										'class' => 'input_translation_tab_content')
								)
				)
		));
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		//->add('id')
		->addIdentifier('nom', null, array('label' => 'Nom'))
		->add('ordre')
		->add('link', null, array('label' => 'Pàgina vinculada'))
		->add('actiu')
		->add('arxiu')
		->add('_action', 'actions', array(
				'actions' => array(
						//'view' => array(),
						'edit' => array(),
						//'delete' => array(),
				), 'label' => 'Accions'));	
	}
	
	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'ASC', // sort direction
			'_sort_by' => 'ordre' // field name
	);

}