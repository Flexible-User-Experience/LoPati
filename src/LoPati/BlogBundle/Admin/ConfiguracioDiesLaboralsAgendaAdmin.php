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


class ConfiguracioDiesLaboralsAgendaAdmin extends Admin
{
	
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
		->remove('create')
		->remove('delete')
		->remove('edit')
		;
	}
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper->add('id', null, array('required' => true));
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->add('id')
		->add('name', null, array('label' => 'Dia'))
		->add('active', null, array('label' => 'És laboral?', 'editable' => true))
		->add('_action', 'actions', array(
				'actions' => array(
						//'view' => array(),
						'edit' => array(),
						'delete' => array(),
				), 'label' => 'Accions'));
	}
	
}	