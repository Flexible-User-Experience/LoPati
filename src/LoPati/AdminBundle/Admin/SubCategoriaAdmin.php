<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Class SubCategoriaAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class SubCategoriaAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'menu/level/2';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'nom' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
            ->add('nom', null, array('label' => 'Nom'))
            ->add('categoria', 'sonata_type_model', array('expanded' => false, 'label' => 'Menú primer nivell'))
            ->add('link', null, array('label' => 'Pàgina vinculada', 'required' => false))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('llista', null, array('label' => 'És llista?'))
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
                    'translatable_class' => 'LoPati\MenuBundle\Entity\SubCategoria',
                )
            )
            ->end();
    }

    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add('nom', null, array('label' => 'Nom', 'editable' => true))
            ->add('categoria', null, array('label' => 'Menú primer nivell'))
            ->add('link', null, array('label' => 'Pàgina vinculada'))
            ->add('llista', 'boolean', array('label' => 'És llista', 'editable' => true))
            ->add('ordre', 'integer', array('label' => 'Posició', 'editable' => true))
            ->add('actiu', 'boolean', array('editable' => true))
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
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('nom')
            ->add('categoria', null, array('label' => 'Menú primer nivell'))
            ->add('link', null, array('label' => 'Pàgina vinculada'))
            ->add('llista')
            ->add('ordre', null, array('label' => 'Posició'))
            ->add('actiu');
    }
}
