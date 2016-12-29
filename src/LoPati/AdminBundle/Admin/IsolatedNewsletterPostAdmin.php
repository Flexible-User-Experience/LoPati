<?php

namespace LoPati\AdminBundle\Admin;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
        $imageHelp = $this->getCustomImageHelperFormMapperWithThumbnail();
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'newsletter',
                null,
                array(
                    'attr' => array(
                        'hidden' => true,
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
                'textarea',
                array(
                    'label'    => 'Descripció',
                    'required' => false,
                    'attr'     => array(
                        'class'      => 'tinymce',
                        'data-theme' => 'simple',
                        'style'      => 'width:100%;height:300px;'
                    ),
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
                    'required' => false,
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

    /**
     * Get image helper form mapper with thumbnail
     *
     * @param string $image
     * @param string $file
     *
     * @return string
     */
    private function getCustomImageHelperFormMapperWithThumbnail($image = 'Image', $file = 'imageFile')
    {
        /** @var CacheManager $lis */
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        /** @var UploaderHelper $vus */
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');
        $getImage = 'get' . $image;

        return ($this->getSubject() ? $this->getSubject()->$getImage() ? '<img src="' . $lis->getBrowserPath(
                    $vus->asset($this->getSubject(), $file),
                    '480xY'
                ) . '" class="admin-preview" style="width:100%;" alt=""/>' : '' : '');
    }
}
