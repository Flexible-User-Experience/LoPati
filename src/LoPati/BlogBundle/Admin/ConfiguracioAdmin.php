<?php
namespace LoPati\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sonata\AdminBundle\Route\RouteCollection;

use Knp\Menu\ItemInterface as MenuItemInterface;


class ConfiguracioAdmin extends Admin
{
	
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
		->remove('create')
		->remove('delete')
		;
	}
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
	->add('adresa',null,array('required' => false,'label'=>'Adreça','attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;')))
	->add('horari',null,array('required' => false,'label'=>'Horari','attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;')) )
	->add('organitza',null,array('required' => false,'label' => 'Administracions','attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;')))
	->add('colabora',null,array('required' => false,'label' => 'Col·labora','attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;')))
	->with('Traduccions')
	->add('translations', 'a2lix_translations',array(
			'label'=>' ',
			'by_reference' => false,
			'required' => false,
			'fields' => array(
					                  // [Optionnal] Fields configurations. If not, auto detection from translatable annotations
					'horari' => array(
							'label' => 'Horari',                   // Custom label
							'attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;'),'label' => 'Horari'),
					'organitza' => array(
							'label' => 'Administracions',                   // Custom label
							'attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;'),'label' => 'Administracions'),
					
					'colabora' => array(
							'label' => 'Col·labora',                   // Custom label
							'attr' => array(
									'class' => 'tinymce',
									'data-theme'=>'simple',
									'style' => 'width: 600px; height: 400px;'),'label' => 'Col·labora')
					
			)
				
	));
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->addIdentifier('id')
		->add('adresa', null, array('label' => 'Adreça'))
		->add('horari')
		->add('organitza')
		->add('colabora', null, array('label' => 'Col·labora'))
		->add('_action', 'actions', array(
				'actions' => array(
						//'view' => array(),
						'edit' => array(),
						'delete' => array(),
				), 'label' => 'Accions'));
	}
	
}	