<?php

namespace LoPati\AdminBundle\Admin;

use Lopati\NewsletterBundle\Repository\NewsletterGroupRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterAdmin extends Admin
{
    protected $baseRoutePattern = 'newsletter';

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'numero' // field name
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var NewsletterGroupRepository $ngr */
        $ngr = $this->configurationPool->getContainer()->get('doctrine.orm.entity_manager')->getRepository('NewsletterBundle:NewsletterGroup');
        $formMapper
            ->add('numero', null, array('label' => 'Núm. newsletter'))
            ->add(
                'dataNewsletter',
                'date',
                array('label' => 'Data publicació', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy')
            )
            ->add('name', null, array('label' => 'Nom'))
            ->add('group', 'sonata_type_model', array(
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'btn_add' => false,
                    'label' => 'Grup',
                    'query' => $ngr->getActiveItemsSortByNameQuery(),
                ))
            ->add(
                'pagines',
                null,
                array('label' => 'Pàgines')
            )
            //->add('estat')
            ->setHelps(array('dataNewsletter' => 'Format: dd-MM-yyyy'));
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('numero', null, array('label' => 'Núm.'))
            ->add(
                'dataNewsletter',
                null,
                array(
                    'label'    => 'Data',
                    'template' => 'AdminBundle:Newsletter:list_custom_dataNewsletter_field.html.twig'
                )
            )
            ->add('name', null, array('label' => 'Nom'))
            ->add('group', null, array('label' => 'Grup'))
            ->add('test')
            ->add(
                'estat',
                null,
                array('label' => 'Estat', 'template' => 'AdminBundle:Newsletter:list_custom_estat_field.html.twig')
            )
//            ->add('enviats')
            ->add('subscrits', null, array('label' => 'Completat', 'template' => 'AdminBundle:Newsletter:list_percentage_completed_field.html.twig'))
            ->add(
                'iniciEnviament',
                null,
                array(
                    'label'    => 'Inici enviament',
                    'template' => 'AdminBundle:Newsletter:list_custom_iniciEnviament_field.html.twig'
                )
            )
            ->add(
                'fiEnviament',
                null,
                array(
                    'label'    => 'Fi enviament',
                    'template' => 'AdminBundle:Newsletter:list_custom_fiEnviament_field.html.twig'
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
                        'test'    => array(
                            'template' => 'AdminBundle:Newsletter:testLink.html.twig'
                        ),
                        'enviar'  => array(
                            'template' => 'AdminBundle:Newsletter:enviar.html.twig'
                        ),
                        'edit' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add(
            'enviar',
            $this->getRouterIdParameter() . '/enviar'
        );
        $collection->add(
            'preview',
            $this->getRouterIdParameter() . '/preview'
        );
        $collection->add(
            'test',
            $this->getRouterIdParameter() . '/test'
        );
        $collection->remove('delete');
        $collection->remove('batch');
        $collection->remove('show');
        $collection->remove('export');
    }
}
