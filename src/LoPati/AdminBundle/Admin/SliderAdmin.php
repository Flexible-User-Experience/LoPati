<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SliderAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'slider';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'position' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
            ->add('imageFile', 'file', array('label' => 'Arxiu imatge', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('Image')))
            ->add('name', null, array('label' => 'Nom'))
            ->add('link', null, array('label' => 'Enllaç'))
            ->add('altName', null, array('label' => 'Alt (SEO)'))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('position', 'integer', array('label' => 'Posició'))
            ->add('active', 'checkbox', array('label' => 'Actiu', 'required' => false))
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add(
                'image',
                null,
                array('label' => 'Imatge', 'template' => 'AdminBundle:Admin:list_custom_image_field.html.twig')
            )
            ->add('name', null, array('label' => 'Nom', 'editable' => true))
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
