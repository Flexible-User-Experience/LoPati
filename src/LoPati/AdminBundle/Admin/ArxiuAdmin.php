<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ArxiuAdmin extends Admin
{
    protected $baseRoutePattern = 'archive';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'any' // field name
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
            ->with('General')
            ->add('any', 'integer', array('label' => 'Any'))
            ->add('actiu', 'checkbox', array('required' => false))
            ->add('imagePetita', 'file', array('label' => 'Imatge any', 'required' => false))
            ->add('imagePetitaName', 'text', array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->add('imagePetita2', 'file', array('label' => 'Imatge any vermell', 'required' => false))
            ->add('imagePetita2Name', 'text', array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->end()
            ->setHelps(array('any' => 'Ex: 2012'));
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            //->addIdentifier('id')
            ->addIdentifier('any')
            ->add(
                'imagePetitaName',
                null,
                array('label' => 'Imatge any', 'template' => 'AdminBundle:Admin:customarxiuimglistfield.html.twig')
            )
            ->add(
                'imagePetita2Name',
                null,
                array('label' => 'Imatge any vermell', 'template' => 'AdminBundle:Admin:customarxiuredimglistfield.html.twig')
            )
            ->add('actiu', 'boolean', array('editable' => true))
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
    }
}
