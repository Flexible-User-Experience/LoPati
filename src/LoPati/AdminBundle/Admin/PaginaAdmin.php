<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PaginaAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'page';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'data_publicacio' // field name
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
        $collection->add('duplicate', $this->getRouterIdParameter() . '/duplicate');
        $collection->remove('show');
        $collection->remove('batch');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(8))
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
                        'style'      => 'width:100%;height:400px;'
                    ),
                    'label' => 'Descripció'
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('actiu', null, array('label' => 'Activa ?', 'required' => false))
            ->add('categoria', 'sonata_type_model', array('label' => 'Menú 1er nivell'), array())
            ->add('subCategoria', 'sonata_type_model', array('label' => 'Menú 2on nivell', 'required' => false))
            ->add(
                'data_publicacio',
                'sonata_type_date_picker',
                array('label' => 'Data publicació', 'format' => 'd/M/y')
            )
            ->add('data_visible', null, array('label' => 'Data visible ?', 'required' => false))
            ->add(
                'data_caducitat',
                'sonata_type_date_picker',
                array(
                    'label'    => 'Data caducitat',
                    'widget'   => 'single_text',
                    'format'   => 'd/M/y',
                    'required' => false
                )
            )
            ->add('data_realitzacio', null, array('label' => 'Data realització'))
            ->add('lloc', null, array('label' => 'Lloc'))
            ->add('video', 'url', array('required' => false))
            ->add('compartir', null, array('label' => 'Compartir ?', 'required' => false))
            ->end()
            ->with('Traduccions', $this->getFormMdSuccessBoxArray(8))
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
                                'style' => 'height:90px;width:100%;'
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
                                'style'      => 'width:100%;height:400px;display:block;'
                            )
                        ),
                    )
                )
            )
            ->end()
            ->with('Portada', $this->getFormMdSuccessBoxArray(4))
            ->add('portada', null, array('label' => 'És portada ?', 'required' => false))
            ->add('imagePetita', 'file', array('label' => 'Imatge petita gris', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('imagePetitaName', 'imagePetita')))
            ->add('imagePetitaName', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->add('imagePetita2', 'file', array('label' => 'Imatge petita vermell', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('imagePetita2Name', 'imagePetita2')))
            ->add('imagePetita2Name', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->end()
            ->with('Imatge principal', $this->getFormMdSuccessBoxArray(4))
            ->add('imageGran1', 'file', array('label' => 'Imatge principal', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('imageGran1Name', 'imageGran1')))
            ->add('imageGran1Name', null, array('label' => 'Nom', 'required' => false, 'read_only' => true,))
            ->add('peuImageGran1', null, array('label' => 'Peu imatge', 'required' => false))
            ->end()
            ->with('Documents adjunts', $this->getFormMdSuccessBoxArray(4))
            ->add('document1', 'file', array('label' => 'Document 1', 'required' => false))
            ->add('document1Name', null, array('label' => 'Nom 1', 'required' => false, 'read_only' => true,))
            ->add('titolDocument1', null, array('label' => 'Títol 1', 'required' => false))
            ->add('document2', 'file', array('label' => 'Document 2', 'required' => false))
            ->add('document2Name', null, array('label' => 'Nom 2', 'required' => false, 'read_only' => true,))
            ->add('titolDocument2', null, array('label' => 'Títol 2', 'required' => false))
            ->end()
            ->with('Enllaços', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'links',
                'textarea',
                array(
                    'required' => false,
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width:100%;height:400px;'
                    ),
                    'label'    => 'Enllaços'
                )
            )
            ->add('urlVimeo', null, array('required' => false, 'label' => 'URL video Vimeo'))
            ->add('urlFlickr', null, array('required' => false, 'label' => 'URL galeria Flickr'))
            ->end()
            ->with('Agenda', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'startDate',
                'sonata_type_date_picker',
                array('label' => 'Data inici', 'format' => 'd/M/y', 'required' => false)
            )
            ->add(
                'endDate',
                'sonata_type_date_picker',
                array('label' => 'Data fi', 'format' => 'd/M/y', 'required' => false)
            )
            ->add('alwaysShowOnCalendar', null, array('label' => 'Mostrar sempre al calendari ?', 'required' => false))
            ->end()
            ->setHelps(
                array(
                    'tipus'                => 'tria el tipus',
                    'compartir'            => 'Mostrar botos per compartir xarxes socials',
                    'urlFlickr'            => 'http://www.exemple.com/(...)',
                    'resum'                => 'Max: 300 caràcters',
                    'data_caducitat'       => 'Data fins quan sera visible la pàgina -> Automaticament serà Arxiu. Deixar en blanc per no caducar.',
                    'alwaysShowOnCalendar' => 'Marcat es mostrarà sempre al calendari l\'event, encara que sigui fora de l\'horari del centre',
                )
            );
    }

    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add('id')
            ->add(
                'data_publicacio',
                null,
                array('label' => 'Data publicació', 'template' => 'AdminBundle:Admin:list_custom_date_field.html.twig')
            )
            ->addIdentifier('titol', null, array('label' => 'Títol'))
            ->add('categoria', null, array('label' => 'Menú 1er nivell'))
            ->add('subcategoria', null, array('label' => 'Menú 2on nivell'))
            ->add('portada', 'boolean', array('label' => 'És portada', 'editable' => true))
            ->add('actiu', 'boolean', array('label' => 'Activa', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit'   => array('template' => 'AdminBundle:Admin:list__action_edit_button.html.twig'),
                        'delete' => array('template' => 'AdminBundle:Admin:list__action_delete_button.html.twig'),
                    ),
                    'label'   => 'Accions'
                )
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titol', null, array('label' => 'Títol'))
            ->add('actiu', null, array('label' => 'Activa'))
            ->add('portada', null, array('label' => 'És portada'))
//            ->add('data_publicacio', null, array('label' => 'Data publicació'))
            ->add('data_publicacio', 'doctrine_orm_date', array('label' => 'Data publicació'), null, array('widget' => 'single_text', 'required' => false,  'attr' => array('class' => 'datepicker')))
            ->add('categoria', null, array('label' => 'Menú 1er nivell'))
            ->add('subCategoria', null, array('label' => 'Menú 2on nivell'));
    }
}
