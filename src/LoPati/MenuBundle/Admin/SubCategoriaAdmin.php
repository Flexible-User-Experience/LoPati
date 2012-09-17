<?php
namespace LoPati\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SubCategoriaAdmin extends Admin
{
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		//->add('id')
		->add('nom', null, array('label' => 'Nom'))
		->add('ordre', null, array('label' => 'Ordre'))
		->add('actiu', null, array('label' => 'Actiu'))
		->add('llista', null, array('label' => 'És llista?'))
		//->add('categoria', null, array('label' => 'Menú primer nivell', 'required' => true) )
		/*->add('categoria', 'entity', array(
				'class' => 'MenuBundle:Categoria',
				'property' => 'nom'
		))*/
		->add('link',null,array('label' => 'Pàgina vinculada','required'  => false))
		->add('categoria', 'sonata_type_model', array('expanded' => false, 'label' => 'Menú primer nivell'))
		->with('Traduccions')
		->add('translations', 'a2lix_translations', array(
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
		//->addIdentifier('id')
		->addIdentifier('nom', null, array('label' => 'Nom'))
		->add('categoria', null, array('label' => 'Menú primer nivell'))
		->add('ordre')
		->add('actiu')
		->add('llista', null, array('label' => 'És llista'))
		->add('link', null, array('label' => 'Pàgina vinculada'))
		->add('_action', 'actions', array(
				'actions' => array(
						//'view' => array(),
						'edit' => array(),
						'delete' => array(),
				)));
	}
	
	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'ASC', // sort direction
			'_sort_by' => 'nom' // field name
	);
}