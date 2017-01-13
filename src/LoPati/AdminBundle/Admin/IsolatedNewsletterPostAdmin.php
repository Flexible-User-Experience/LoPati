<?php

namespace LoPati\AdminBundle\Admin;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use LoPati\NewsletterBundle\Enum\NewsletterTypeEnum;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use LoPati\AdminBundle\Form\Type\EditIsolatedNewsletterPostActionButtonFormType;

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
        if ($this->getRootCode() != $this->getCode()) {
            // is edit mode, disable on new subjects and is children
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
                        'required' => false,
                    )
                )
                ->add(
                    'type',
                    ChoiceType::class,
                    array(
                        'label'    => 'Tipus',
                        'choices'  => NewsletterTypeEnum::getEnumArray(),
                        'multiple' => false,
                        'expanded' => false,
                        'required' => false,
                        'disabled' => false,
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
            ;
            if ($this->id($this->getSubject())) {
                $formMapper
                    ->add(
                        'fakeAction',
                        EditIsolatedNewsletterPostActionButtonFormType::class,
                        array(
                            'text'     => 'Editar article',
                            'url'      => $this->generateObjectUrl('edit', $this->getSubject()),
                            'label'    => 'Accions',
                            'mapped'   => false,
                            'required' => false,
                        )
                    );
            }
            $formMapper->end();
        } else {
            // else is normal admin view
            $formMapper
                ->with('General', $this->getFormMdSuccessBoxArray(6))
                ->add(
                    'title',
                    null,
                    array(
                        'label'    => 'Títol',
                        'required' => false,
                    )
                )
                ->add(
                    'shortDescription',
                    'textarea',
                    array(
                        'label'    => 'Descripció breu',
                        'required' => false,
                        'attr'     => array(
                            'rows' => 12,
                        ),
                    )
                )
                ->end()
                ->with('Dates', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'date',
                    'sonata_type_date_picker',
                    array(
                        'label'    => 'Data inici',
                        'format'   => 'd/M/y',
                        'required' => false,
                    )
                )
                ->add(
                    'endDate',
                    'sonata_type_date_picker',
                    array(
                        'label'    => 'Data fi',
                        'format'   => 'd/M/y',
                        'required' => false,
                    )
                )
                ->add(
                    'intervalDateText',
                    null,
                    array(
                        'label'    => 'Interval o data en forma de text',
                        'required' => false,
                        'help'     => 'si escrius algun text, la data inci o fi no apareixeran al newsletter',
                    )
                )
                ->end()
                ->with('Controls', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'type',
                    ChoiceType::class,
                    array(
                        'label'    => 'Tipus',
                        'choices'  => NewsletterTypeEnum::getEnumArray(),
                        'multiple' => false,
                        'expanded' => false,
                        'required' => false,
                        'disabled' => false,
                    )
                )
//                ->add(
//                    'newsletter',
//                    null,
//                    array(
//                        'disabled' => true,
//                    )
//                )
                ->add(
                    'position',
                    null,
                    array(
                        'label'    => 'Posició',
                        'required' => false,
                    )
                )
                ->end()
                ->with('Imatge', $this->getFormMdSuccessBoxArray(5))
                ->add(
                    'imageFile',
                    'file',
                    array(
                        'label'       => 'Imatge',
                        'required'    => true,
                        'help'        => $imageHelp,
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
                ->end()
            ;
        }

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
                    '240xY'
                ) . '" class="admin-preview" style="width:100%;" alt=""/>' : '' : '');
    }
}
