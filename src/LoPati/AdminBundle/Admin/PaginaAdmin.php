<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PaginaAdmin extends Admin
{
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'titol' // field name
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
            ->add('tipus', 'choice', array('choices' => array('w' => 'Web', 'b' => 'Bloc'), 'required' => true,))
            ->add('titol', null, array('label' => 'Títol'))
            ->add(
                'resum',
                'textarea',
                array('label' => 'Resum', 'required' => false, 'attr' => (array('style' => 'height:90px;')))
            )
            ->add(
                'descripcio',
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
            ->add('actiu', null, array('label' => 'Activa ?', 'required' => false))
            ->add('categoria', 'sonata_type_model', array('label' => 'Menú primer nivell'), array())
            ->add('subCategoria', 'sonata_type_model', array('label' => 'Menú segon nivell', 'required' => false))
            ->add(
                'data_publicacio',
                'date',
                array('label' => 'Data publicació', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy')
            )
            ->add('data_visible', null, array('label' => 'Data visible ?', 'required' => false))
            ->add(
                'data_caducitat',
                'date',
                array(
                    'label'    => 'Data caducitat',
                    'widget'   => 'single_text',
                    'format'   => 'dd-MM-yyyy',
                    'required' => false
                )
            )
            ->add('data_realitzacio', null, array('label' => 'Data realització'))
            ->add('lloc', null, array('label' => 'Lloc'))
            ->add('video', 'url', array('required' => false))
            ->add('compartir', null, array('label' => 'Compartir ?', 'required' => false))

            ->with('Imatge principal')
            ->add('imageGran1', 'file', array('label' => 'Arxiu', 'required' => false))
            ->add('imageGran1Name', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->add('peuImageGran1', null, array('label' => 'Peu imatge', 'required' => false))

            ->with('Portada')
            ->add('portada', null, array('label' => 'És portada ?', 'required' => false))
            ->add('imagePetita', 'file', array('label' => 'Imatge petita gris', 'required' => false))
            ->add('imagePetitaName', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->add('imagePetita2', 'file', array('label' => 'Imatge petita vermell', 'required' => false))
            ->add('imagePetita2Name', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))

            ->with('Documents adjunts')
            ->add('document1', 'file', array('label' => 'Arxiu 1', 'required' => false))
            ->add('document1Name', null, array('label' => 'Nom 1', 'required' => false, 'read_only' => true,))
            ->add('titolDocument1', null, array('label' => 'Títol 1', 'required' => false))
            ->add('document2', 'file', array('label' => 'Arxiu 2', 'required' => false))
            ->add('document2Name', null, array('label' => 'Nom 2', 'required' => false, 'read_only' => true,))
            ->add('titolDocument2', null, array('label' => 'Títol 2', 'required' => false))

            ->with('Enllaços')
            ->add(
                'links',
                'textarea',
                array(
                    'required' => false,
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width: 600px; height: 400px;'
                    ),
                    'label'    => 'Enllaços'
                )
            )
            ->add('urlVimeo', null, array('required' => false, 'label' => 'URL video Vimeo'))
            ->add('urlFlickr', null, array('required' => false, 'label' => 'URL galeria Flickr'))

            ->with('Agenda')
            ->add(
                'startDate',
                'date',
                array('label' => 'Data inici', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'required' => false)
            )
            ->add(
                'endDate',
                'date',
                array('label' => 'Data fi', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'required' => false)
            )
            ->add('alwaysShowOnCalendar', null, array('label' => 'Mostrar sempre al calendari ?', 'required' => false))

            ->with('Traduccions')
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                array(
                    'label'        => ' ',
                    'required'     => false,
                    'translatable_class' => 'LoPati\BlogBundle\Entity\Pagina',
                    'fields'   => array(
                        'titol'            => array('label' => 'Títol'),
                        'resum'            => array(
                            'attr' => array(
                                'style' => 'height:90px;width:480px;'
                            )
                        ),
                        'data_realitzacio' => array(
                            'label' => 'Data realització',
                        ),
                        'lloc'             => array(),
                        'peuImageGran1'    => array(
                            'label' => 'Peu imatge principal',
                        ),
                        'descripcio'       => array(
                            'label' => 'Descripció', // Custom label
                            'attr'  => array(
                                'class'      => 'tinymce',
                                'data-theme' => 'simple',
                                'style'      => 'width: 600px; height: 400px; display: block;'
                            )
                        ),
                    )
                )
            )
            ->setHelps(
                array(
                    'tipus'                => 'tria el tipus',
                    'compartir'            => 'Mostrar botos per compartir xarxes socials',
                    'urlFlickr'            => 'Ex: http://www.flickr.com/photos/lopati/..',
                    'resum'                => 'Max: 300 caràcters',
                    'data_publicacio'      => 'Format: dd-MM-yyyy',
                    'data_caducitat'       => 'Data fins quan sera visible la pàgina -> Automaticament serà Arxiu. Deixar en blanc per no caducar. Format: dd-MM-yyyy',
                    'alwaysShowOnCalendar' => 'Marcat es mostrarà sempre al calendari l\'event, encara que sigui fora de l\'horari del centre',
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('id')
            ->add(
                'data_publicacio',
                null,
                array('label' => 'Data publicació', 'template' => 'AdminBundle:Admin:list_custom_date_field.html.twig')
            )
            ->addIdentifier('titol', null, array('label' => 'Títol'))
            ->add('categoria', null, array('label' => 'Menú primer nivell'))
            ->add('subcategoria', null, array('label' => 'Menú segon nivell'))
            ->add('portada', 'boolean', array('label' => 'És portada', 'editable' => true))
            ->add('actiu', 'boolean', array('label' => 'Activa', 'editable' => true))
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
            ->add('titol')
            ->add('actiu')
            ->add('portada')
            ->add('data_publicacio')
            ->add('categoria')
            ->add('subCategoria');
    }
}
