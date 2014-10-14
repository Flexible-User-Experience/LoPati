<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ArtistaAdmin extends Admin
{
    protected $baseRoutePattern = 'artist';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'name' // field name
    );

    /**
     * Configure export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array('xls', 'csv');
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
            ->add('name', null, array('label' => 'Nom'))
            ->add('category', null, array('label' => 'Especialitat'))
            ->add('city', null, array('label' => 'Ciutat'))
            ->add('year', null, array('label' => 'Any'))
            ->add('webpage', null, array('label' => 'Web'))
            ->add('active', null, array('label' => 'Activat'))
            ->add(
                'summary',
                'textarea',
                array('label' => 'Resum', 'required' => false, 'attr' => (array('style' => 'height:90px;')))
            )
            ->add(
                'description',
                'textarea',
                array(
                    'attr'  => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    ),
                    'label' => 'Descripció'
                )
            )

            ->with('Imatges')
            ->add('image1File', 'file', array('label' => 'Imatge 1', 'required' => false))
            ->add('image1', null, array('label' => 'Nom imatge 1', 'required' => false, 'read_only' => true,))
            ->add('image2File', 'file', array('label' => 'Imatge 2', 'required' => false))
            ->add('image2', null, array('label' => 'Nom imatge 2', 'required' => false, 'read_only' => true,))
            ->add('image3File', 'file', array('label' => 'Imatge 3', 'required' => false))
            ->add('image3', null, array('label' => 'Nom imatge 3', 'required' => false, 'read_only' => true,))
            ->add('image4File', 'file', array('label' => 'Imatge 4', 'required' => false))
            ->add('image4', null, array('label' => 'Nom imatge 4', 'required' => false, 'read_only' => true,))
            ->add('image5File', 'file', array('label' => 'Imatge 5', 'required' => false))
            ->add('image5', null, array('label' => 'Nom imatge 5', 'required' => false, 'read_only' => true,))

            ->with('Documents')
            ->add('document1', 'file', array('label' => 'CV', 'required' => false))
            ->add('document1Name', null, array('label' => 'Nom CV', 'required' => false, 'read_only' => true,))

            ->with('Traduccions')
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\ArtistaBundle\Entity\Artista',
                    'fields'   => array(
                        'summary'     => array(
                            'label' => 'Resum',
                            'attr'  => array(
                                'style' => 'height:90px;width:480px;'
                            )
                        ),
                        'description' => array(
                            'label' => 'Descripció',
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px; display: block;'
                            )
                        ),
                    )
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
//            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Nom'))
            ->add('category', null, array('label' => 'Especialitat', 'editable' => true))
            ->add('city', null, array('label' => 'Ciutat', 'editable' => true))
            ->add('year', null, array('label' => 'Any', 'editable' => true))
            ->add('webpage', null, array('label' => 'Web', 'editable' => true))
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
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('category', null, array('label' => 'Especialitat'))
            ->add('city', null, array('label' => 'Ciutat'))
            ->add('year', null, array('label' => 'Any'))
            ->add('webpage', null, array('label' => 'Web'))
            ->add('active', null, array('label' => 'Actiu'));
    }
}
