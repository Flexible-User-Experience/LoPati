<?php

namespace LoPati\AdminBundle\Admin;

use LoPati\NewsletterBundle\Enum\NewsletterStatusEnum;
use Lopati\NewsletterBundle\Repository\NewsletterGroupRepository;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class IsolatedNewsletterAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class IsolatedNewsletterAdmin extends AbstractBaseAdmin
{
    protected $baseRoutePattern = 'newsletter/newsletter';

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
        /** @var NewsletterGroupRepository $ngr */
        $ngr = $this->configurationPool->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NewsletterBundle:NewsletterGroup');
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'subject',
                null,
                array(
                    'label' => 'Títol'
                )
            )
//            ->add(
//                'pagines',
//                null,
//                array('label' => 'Pàgines')
//            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'date',
                'sonata_type_date_picker',
                array(
                    'label' => 'Data',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'group',
                'sonata_type_model',
                array(
                    'label'    => 'Grup',
                    'query'    => $ngr->getActiveItemsSortByNameQuery(),
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'btn_add'  => false,
                )
            )
            ->end()
            ->with('Informació', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'state',
                ChoiceType::class,
                array(
                    'label'    => 'Estat',
                    'choices'  => NewsletterStatusEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'disabled' => true,
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
                'date',
                null,
                array(
                    'label'    => 'Data',
                    'template' => 'AdminBundle:Newsletter:list_custom_date_field.html.twig',
                )
            )
            ->add(
                'subject',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'Grup',
                )
            )
            ->add('tested')
            ->add(
                'state',
                null,
                array(
                    'label'    => 'Estat',
                    'template' => 'AdminBundle:Newsletter:list_custom_estat_field.html.twig',
                )
            )
            ->add(
                'beginSend',
                null,
                array(
                    'label'    => 'Inici enviament',
                    'template' => 'AdminBundle:Newsletter:list_custom_iniciEnviament_field.html.twig',
                )
            )
            ->add(
                'endSend',
                null,
                array(
                    'label'    => 'Fi enviament',
                    'template' => 'AdminBundle:Newsletter:list_custom_fiEnviament_field.html.twig',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'preview' => array(
                            'template' => 'AdminBundle:Newsletter:previewLink.html.twig'
                        ),
                        'test' => array(
                            'template' => 'AdminBundle:Newsletter:testLink.html.twig'
                        ),
                        'send' => array(
                            'template' => 'AdminBundle:Newsletter:enviar.html.twig'
                        ),
                        'edit' => array(
                            'template' => 'AdminBundle:Admin:list__action_edit_button.html.twig'
                        ),
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
        $collection->add('send', $this->getRouterIdParameter() . '/send');
        $collection->add('preview', $this->getRouterIdParameter() . '/preview');
        $collection->add('test', $this->getRouterIdParameter() . '/test');
        $collection->remove('delete');
        $collection->remove('batch');
        $collection->remove('show');
        $collection->remove('export');
    }
}
