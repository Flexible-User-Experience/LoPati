<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConfiguracioAdmin extends Admin
{
    /**
     * Configure export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'adresa',
                null,
                array(
                    'required' => false,
                    'label'    => 'Adreça',
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    )
                )
            )
            ->add(
                'horari',
                null,
                array(
                    'required' => false,
                    'label'    => 'Horari',
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    )
                )
            )
            ->add(
                'organitza',
                null,
                array(
                    'required' => false,
                    'label'    => 'Administracions',
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    )
                )
            )
            ->add(
                'colabora',
                null,
                array(
                    'required' => false,
                    'label'    => 'Col·labora',
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    )
                )
            )
            ->with('Traduccions')
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\BlogBundle\Entity\Configuracio',
                    'fields'       => array(
                        'horari'    => array(
                            'label' => 'Horari',
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px;'
                            ),
                        ),
                        'organitza' => array(
                            'label' => 'Administracions',
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px;'
                            ),
                        ),
                        'colabora'  => array(
                            'label' => 'Col·labora',
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px;'
                            ),
                        )
                    )
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            //->addIdentifier('id')
            ->add('adresa', null, array('label' => 'Adreça', 'template' => 'AdminBundle:Admin:customadresa.html.twig'))
            ->add('horari', null, array('label' => 'Horari', 'template' => 'AdminBundle:Admin:customhorari.html.twig'))
            ->add('organitza', null, array('label' => 'Organitza', 'template' => 'AdminBundle:Admin:customorganitza.html.twig'))
            ->add('colabora', null, array('label' => 'Col·labora', 'template' => 'AdminBundle:Admin:customcolabora.html.twig'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'   => array(),
                        'delete' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
    }
}
