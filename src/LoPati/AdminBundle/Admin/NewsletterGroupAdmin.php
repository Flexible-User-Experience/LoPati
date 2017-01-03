<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Newsletter grup';
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
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add('name', null, array('label' => 'Nom'))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('active', null, array('label' => 'Actiu', 'required' => false))
            ->end()
            ->with('Usuaris', $this->getFormMdSuccessBoxArray(10))
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
            ->end()
        ;
        $this->setTemplate('edit', 'AdminBundle:Admin:custom_base_edit_ajax.html.twig');
    }

    /**
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->addIdentifier('name', null, array('label' => 'Nom'))
            ->add('users', null, array('label' => 'Usuaris'))
            ->add('active', 'boolean', array('label' => 'Actiu', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => 'AdminBundle:Admin:list__action_edit_button.html.twig'),
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
