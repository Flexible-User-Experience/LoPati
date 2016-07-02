<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Class ArtistaAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ArtistaAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'artist';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'name' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
            ->add('name', null, array('label' => 'Nom'))
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
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('category', null, array('label' => 'Especialitat'))
            ->add('city', null, array('label' => 'Ciutat'))
            ->add('year', null, array('label' => 'Any'))
            ->add('webpage', null, array('label' => 'Web'))
            ->add('active', null, array('label' => 'Activat'))
            ->end()
            ->with('Imatges', $this->getFormMdSuccessBoxArray(6))
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
            ->end()
            ->with('Documents')
            ->add('document1', 'file', array('label' => 'CV', 'required' => false))
            ->add('document1Name', null, array('label' => 'Nom CV', 'required' => false, 'read_only' => true,))
            ->end()
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
        unset($this->listModes['mosaic']);
        $mapper
            ->add('name', null, array('label' => 'Nom', 'editable' => true))
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
                        'edit' => array('template' => 'AdminBundle:Admin:list__action_edit_button.html.twig'),
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
            ->add('summary', null, array('label' => 'Resum'))
            ->add('description', null, array('label' => 'Descripció'))
            ->add('active', null, array('label' => 'Actiu'));
    }
}
