<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterGroupAdmin extends Admin
{
    protected $baseRoutePattern = 'newsletter/group';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'name' // field name
    );

    /**
     * Configure export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array();
    }

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection collection
     *
     * @return mixed
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Nom'))
//            ->add('users', 'genemu_jqueryselect2_hidden', array(
//                    'configs' => array(
//                        'multiple' => true // Wether or not multiple values are allowed (default to false)
//                    )
//                ))
            // TODO: make this asynchronous (AJAX)
            ->add(
                'users',
                'genemu_jqueryselect2_entity',
                array(
                    'label' => 'Usuaris',
                    'class' => 'LoPati\NewsletterBundle\Entity\NewsletterUser',
                    'multiple' => true,
                    'required' => false,
                )
            )
            ->add('active', null, array('label' => 'Actiu', 'required' => false))
        ;
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name', null, array('label' => 'Nom'))
            ->add('users', null, array('label' => 'Usuaris'))
            ->add('active', 'boolean', array('label' => 'Actiu', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('active', null, array('label' => 'Actiu'));
    }
}
