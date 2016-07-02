<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterGroupAdmin extends AbstractAdmin
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

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('name', null, array('label' => 'Nom'))
            ->add(
                'users',
                'genemu_jqueryselect2_hidden',
                array(
                    'label' => 'Usuaris',
                    'by_reference' => true,
                    'compound' => false,
                    'data' => array(0), // don't remove this, mandatory to trigger client-side Select2 initializer event
//                    'transformer' => 'LoPati\NewsletterBundle\Form\DataTransformerNewsletterUserTransformer',
                    'transformer' => 'ModelToIdTransformater',
                    'required' => false,
                    'configs' => array(
                        'multiple' => true
                    ),
                )
            )
            ->add('active', null, array('label' => 'Actiu', 'required' => false))
            ->end()
        ;
        $this->setTemplate('edit', 'AdminBundle:Admin:custom_base_edit_ajax.html.twig');
    }

    /**
     * @param ListMapper $mapper
     */
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

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('active', null, array('label' => 'Actiu'));
    }
}
