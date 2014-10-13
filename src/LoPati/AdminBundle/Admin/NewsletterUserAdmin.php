<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterUserAdmin extends Admin
{
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'email' // field name
    );

    /**
     * Configure export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array('xls', 'csv');
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

            ->add('email')
            ->add(
                'groups',
                'genemu_jqueryselect2_entity',
                array(
                    'label' => 'Grups',
                    'class' => 'LoPati\NewsletterBundle\Entity\NewsletterGroup',
                    'multiple' => true,
                    'disabled' => true,
                    'required' => false,
                )
            )
            ->add(
                'idioma',
                'choice',
                array(
                    'choices'  => array('ca' => 'Català', 'es' => 'Castellano', 'en' => 'English'),
                    'required' => true,
                )
            )
            ->add('active', null, array('label' => 'Actiu', 'required' => false))
        ;
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
//            ->add('id')
            ->add('created', null, array('label' => 'Data alta', 'template' => 'AdminBundle:Admin:list_custom_created_datetime_field.html.twig'))
            ->addIdentifier('email')
            ->add('groups', null, array('label' => 'Grups'))
            ->add('idioma')
            ->add('fail', null, array('label' => 'Enviaments erronis'))
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
            ->add('email')
            ->add('groups', null, array('label' => 'Grup'))
            ->add('active', null, array('label' => 'Actiu'))
            ->add('created', null, array('label' => 'Data Alta'));
    }
}
