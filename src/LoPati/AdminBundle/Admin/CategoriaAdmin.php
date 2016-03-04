<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CategoriaAdmin extends AbstractBaseAdmin
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
        $collection->remove('batch');
        $collection->remove('show');
        $collection->remove('export');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
            ->add('nom', null, array('label' => 'Nom'))
            ->add('link', null, array('label' => 'Pàgina vinculada', 'required' => false))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('ordre', null, array('label' => 'Posició'))
            ->add('arxiu', 'checkbox', array('label' => 'És Arxiu ?', 'required' => false))
            ->add('actiu', null, array('label' => 'Actiu'))
            ->end()
            ->with('Traduccions', $this->getFormMdSuccessBoxArray(8))
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\MenuBundle\Entity\Categoria',
                )
            )
            ->end();
    }

    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            //->add('id')
            ->add('nom', null, array('label' => 'Nom', 'editable' => true))
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
