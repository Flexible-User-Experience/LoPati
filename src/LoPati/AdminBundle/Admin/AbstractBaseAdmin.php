<?php

namespace LoPati\AdminBundle\Admin;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class AbstractBaseAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
abstract class AbstractBaseAdmin extends AbstractAdmin
{
    /**
     * @var array
     */
    protected $perPageOptions = array(25, 50, 100, 200);

    /**
     * @var int
     */
    protected $maxPerPage = 25;

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
        $collection->remove('delete');
        $collection->remove('batch');
    }

    /**
     * Get export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array(
            'csv',
            'xls',
        );
    }

    /**
     * Get image helper form mapper with thumbnail
     *
     * @param string $image
     * @param string $file
     *
     * @return string
     */
    protected function getImageHelperFormMapperWithThumbnail($image = 'Image', $file = 'imageFile')
    {
        /** @var CacheManager $lis */
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        /** @var UploaderHelper $vus */
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');
        $getImage = 'get' . $image;

        return ($this->getSubject()->$getImage() ? '<img src="' . $lis->getBrowserPath(
                $vus->asset($this->getSubject(), $file),
                '480xY'
            ) . '" class="admin-preview" style="width:100%;" alt=""/>' : '') . '<span style="width:100%;display:block;">màmim 5MB amb format PNG, JPG o GIF</span>';
    }

    /**
     * @param string $bootstrapGrid
     * @param string $bootstrapSize
     * @param string $boxClass
     *
     * @return array
     */
    protected function getDefaultFormBoxArray($bootstrapGrid = 'md', $bootstrapSize = '6', $boxClass = 'primary')
    {
        return array(
            'class' => 'col-' . $bootstrapGrid . '-' . $bootstrapSize,
            'box_class' => 'box box-' . $boxClass,
        );
    }

    /**
     * @param string $bootstrapColSize
     *
     * @return array
     */
    protected function getFormMdSuccessBoxArray($bootstrapColSize = '6')
    {
        return $this->getDefaultFormBoxArray('md', $bootstrapColSize, 'success');
    }
}
