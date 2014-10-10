<?php
namespace LoPati\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SubCategoriaAdmin extends Admin
{
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'nom' // field name
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
            ->add('llista', null, array('label' => 'És llista?'))
            ->add('link', null, array('label' => 'Pàgina vinculada', 'required' => false))
            ->add('categoria', 'sonata_type_model', array('expanded' => false, 'label' => 'Menú primer nivell'))
            ->with('Traduccions')
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\MenuBundle\Entity\SubCategoria',
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            //->addIdentifier('id')
            ->addIdentifier('nom', null, array('label' => 'Nom'))
            ->add('categoria', null, array('label' => 'Menú primer nivell'))
            ->add('ordre')
            ->add('actiu')
            ->add('llista', null, array('label' => 'És llista'))
            ->add('link', null, array('label' => 'Pàgina vinculada'))
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
