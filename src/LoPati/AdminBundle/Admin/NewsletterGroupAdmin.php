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
        '_sort_order' => 'ASC',
        '_sort_by'    => 'name',
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
        $collection->remove('batch');
        $collection->remove('show');
        $collection->remove('export');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add(
                'users',
                'genemu_jqueryselect2_hidden',
                array(
                    'label' => 'Usuaris',
                    'by_reference' => false,
                    'data' => array(1), // don't remove this, mandatory to trigger client-side select2JS initiallize event
                    'transformer' => 'LoPati\NewsletterBundle\Form\DataTransformer\NewsletterUserTransformer',
                    'required' => false,
                    'configs' => array(
                        'multiple' => true
                    ),
                )
            )
            ->add('active', null, array('label' => 'Actiu', 'required' => false))
        ;
        $this->setTemplate('edit', 'AdminBundle:Admin:custom_base_edit_ajax.html.twig');
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
