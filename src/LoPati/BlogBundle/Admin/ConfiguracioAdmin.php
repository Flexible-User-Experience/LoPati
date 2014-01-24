<?php
namespace LoPati\BlogBundle\Admin;

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
                'a2lix_translations',
                array(
                    'label'        => ' ',
                    'by_reference' => false,
                    'required'     => false,
                    'fields'       => array(
                        // [Optionnal] Fields configurations. If not, auto detection from translatable annotations
                        'horari'    => array(
                            'label' => 'Horari', // Custom label
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px;'
                            ),
                        ),
                        'organitza' => array(
                            'label' => 'Administracions', // Custom label
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px;'
                            ),
                        ),
                        'colabora'  => array(
                            'label' => 'Col·labora', // Custom label
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
            ->add('adresa', null, array('label' => 'Adreça', 'template' => 'BlogBundle:Admin:customadresa.html.twig'))
            ->add('horari', null, array('label' => 'Horari', 'template' => 'BlogBundle:Admin:customhorari.html.twig'))
            ->add(
                'organitza',
                null,
                array('label' => 'Organitza', 'template' => 'BlogBundle:Admin:customorganitza.html.twig')
            )
            ->add(
                'colabora',
                null,
                array('label' => 'Col·labora', 'template' => 'BlogBundle:Admin:customcolabora.html.twig')
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        //'view' => array(),
                        'edit'   => array(),
                        'delete' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
    }
}	