<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class IsolatedNewsletterPostAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class IsolatedNewsletterPostAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'newsletter/newsletter-post';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'date' // field name
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
     * Create & edit view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $imageHelp = $this->getImageHelperFormMapperWithThumbnail();
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'newsletter',
                null,
                array(
                    'attr' => array(
                        'hidden' => false,
                    )
                )
            )
            ->add(
                'imageFile',
                'file',
                array(
                    'label'       => 'Imatge',
                    'required'    => false,
                    'sonata_help' => $imageHelp,
                    'help'        => $imageHelp,
                )
            )
            ->add(
                'title',
                null,
                array(
                    'label'    => 'Títol',
                    'required' => true,
                )
            )
            ->add(
                'shortDescription',
                null,
                array(
                    'label'    => 'Descripció',
                    'required' => false,
                )
            )
            ->add(
                'link',
                null,
                array(
                    'label'    => 'Enllaç',
                    'required' => false,
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label'    => 'Posició',
                    'required' => true,
                )
            )
            ->end()
        ;
    }

    /**
     * List view
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add(
                'title',
                null,
                array(
                    'label'    => 'Nom',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                    ),
                    'label' => 'Accions',
                )
            );
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
        $collection->remove('batch');
        $collection->remove('show');
        $collection->remove('export');
    }
}
