<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SliderAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'position' // field name
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('imageFile', 'file', array('label' => 'Arxiu imatge', 'required' => false))
            ->add('image', 'text', array('label' => 'Imatge', 'required' => false, 'read_only' => true))
            ->add('link', null, array('label' => 'Enllaç'))
            ->add('altName', null, array('label' => 'Alt (SEO)'))
            ->add('position', 'integer', array('label' => 'Posició'))
            ->add('active', 'checkbox', array('label' => 'Actiu', 'required' => false))
        ;
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('name', null, array('label' => 'Nom', 'editable' => true))
            ->add(
                'image',
                null,
                array('label' => 'Imatge', 'template' => 'AdminBundle:Admin:list_custom_image_field.html.twig')
            )
            ->add('link', null, array('label' => 'Enllaç', 'editable' => true))
            ->add('position', 'integer', array('label' => 'Posició', 'editable' => true))
            ->add('active', 'boolean', array('label' => 'Actiu', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
    }
}
