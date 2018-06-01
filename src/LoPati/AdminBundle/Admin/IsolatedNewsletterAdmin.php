<?php

namespace LoPati\AdminBundle\Admin;

use LoPati\NewsletterBundle\Enum\NewsletterStatusEnum;
use Lopati\NewsletterBundle\Repository\NewsletterGroupRepository;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class IsolatedNewsletterAdmin.
 *
 * @category Admin
 */
class IsolatedNewsletterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Newsletter';
    protected $baseRoutePattern = 'newsletter/newsletter';
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'date', // field name
    );

    /**
     * Configure export formats.
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array();
    }

    /**
     * Create & edit view.
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
                    'label' => 'Títol del missatge',
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'date',
                'sonata_type_date_picker',
                array(
                    'label' => 'Data',
                    'format' => 'd/M/y',
                    'required' => false,
                )
            )
            ->add(
                'group',
                'sonata_type_model',
                array(
                    'label' => 'Grup',
                    'query' => $ngr->getActiveItemsSortByNameQuery(),
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'btn_add' => false,
                )
            )
            ->end()
            ->with('Informació', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'tested',
                null,
                array(
                    'label' => 'Test',
                    'disabled' => true,
                    'required' => false,
                )
            )
            ->add(
                'state',
                ChoiceType::class,
                array(
                    'label' => 'Estat',
                    'choices' => NewsletterStatusEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'disabled' => true,
                )
            )
            ->add(
                'beginSend',
                'sonata_type_date_picker',
                array(
                    'label' => 'Inici enviament',
                    'format' => 'd/M/y',
                    'disabled' => true,
                    'required' => false,
                )
            )
            ->end()
        ;
        if ($this->id($this->getSubject())) {
            // is edit mode, disable on new subjects
            $formMapper
                ->with('Articles', array($this->getFormMdSuccessBoxArray(12)))
                ->add(
                    'posts',
                    'sonata_type_collection',
                    array(
                        'label' => ' ',
                        'cascade_validation' => true,
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                    )
                )
                ->end();
        }
    }

    /**
     * Filter view.
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'date',
                'doctrine_orm_date',
                array(
                    'label' => 'Data',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'subject',
                null,
                array(
                    'label' => 'Títol del missatge',
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'Grup',
                )
            )
            ->add(
                'tested',
                null,
                array(
                    'label' => 'Test',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Estat',
                ),
                'choice',
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => NewsletterStatusEnum::getEnumArray(),
                )
            )
        ;
    }

    /**
     * List view.
     *
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add(
                'date',
                'date',
                array(
                    'label' => 'Data',
                    'editable' => true,
                    'format' => 'd/m/Y',
                )
            )
            ->add(
                'subject',
                null,
                array(
                    'label' => 'Títol del missatge',
                    'editable' => true,
                )
            )
            ->add(
                'group',
                null,
                array(
                    'label' => 'Grup',
                )
            )
            ->add(
                'tested',
                null,
                array(
                    'label' => 'Test',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Estat',
                    'template' => 'AdminBundle:IsolatedNewsletter:list_custom_state_field.html.twig',
                )
            )
            ->add(
                'beginSend',
                null,
                array(
                    'label' => 'Inici enviament',
                    'template' => 'AdminBundle:IsolatedNewsletter:list_custom_begin_send_field.html.twig',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'preview' => array(
                            'template' => 'AdminBundle:IsolatedNewsletter:list__action_preview_button.html.twig',
                        ),
                        'test' => array(
                            'template' => 'AdminBundle:IsolatedNewsletter:list__action_test_button.html.twig',
                        ),
                        'send' => array(
                            'template' => 'AdminBundle:IsolatedNewsletter:list__action_send_button.html.twig',
                        ),
                        'edit' => array(
                            'template' => 'AdminBundle:Admin:list__action_edit_button.html.twig',
                        ),
                        'delete' => array(
                            'template' => 'AdminBundle:Admin:list__action_delete_button.html.twig',
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
        $collection
            ->add('send', $this->getRouterIdParameter().'/send')
            ->add('preview', $this->getRouterIdParameter().'/preview')
            ->add('test', $this->getRouterIdParameter().'/test')
            ->add('upload', 'upload')
            ->add('previewRGPD2018NewsletterAgreement', 'preview-rgpd-2018-newsletter-agreement')
            ->add('testRGPD2018NewsletterAgreement', 'test-rgpd-2018-newsletter-agreement')
            ->add('sendRGPD2018NewsletterAgreement', 'send-rgpd-2018-newsletter-agreement')
            ->remove('batch')
            ->remove('show')
            ->remove('export')
        ;
    }
}
