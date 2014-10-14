<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CategoriaAdmin extends Admin
{
    protected $baseRoutePattern = 'menu/level/1';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'ordre' // field name
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
            //->add('id')
            ->add('nom', null, array('label' => 'Nom'))
            ->add('ordre', null, array('label' => 'Ordre'))
            ->add('actiu', null, array('label' => 'Actiu'))
            ->add('arxiu', 'checkbox', array('label' => 'És Arxiu ?', 'required' => false))
            ->add('link', null, array('label' => 'Pàgina vinculada', 'required' => false))
            ->with('Traduccions')
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\MenuBundle\Entity\Categoria',
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            //->add('id')
            ->addIdentifier('nom', null, array('label' => 'Nom'))
            ->add('link', null, array('label' => 'Pàgina vinculada'))
            ->add('arxiu', 'boolean', array('editable' => true))
            ->add('ordre', 'integer', array('editable' => true))
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
