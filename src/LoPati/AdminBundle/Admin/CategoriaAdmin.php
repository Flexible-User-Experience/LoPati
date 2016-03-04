<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class CategoriaAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class CategoriaAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'menu/level/1';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'ordre' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
            ->add('nom', null, array('label' => 'Nom'))
            ->add('link', null, array('label' => 'Pàgina vinculada', 'required' => false))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('arxiu', 'checkbox', array('label' => 'És Arxiu ?', 'required' => false))
            ->add('ordre', null, array('label' => 'Posició'))
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
            ->add('nom', null, array('label' => 'Nom', 'editable' => true))
            ->add('link', null, array('label' => 'Pàgina vinculada'))
            ->add('arxiu', 'boolean', array('editable' => true))
            ->add('ordre', 'integer', array('label' => 'Posició', 'editable' => true))
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

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('nom')
            ->add('link', null, array('label' => 'Pàgina vinculada'))
            ->add('arxiu')
            ->add('ordre', null, array('label' => 'Posició'))
            ->add('actiu');
    }
}
