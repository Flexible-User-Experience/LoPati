<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class NewsletterUserAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 */
class NewsletterUserAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Newsletter usuari';
    protected $baseRoutePattern = 'newsletter/user';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'created' // field name
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
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('group', 'setgroup');
        $collection->add('setgroup', 'group');
        $collection->remove('delete');
        $collection->remove('show');
    }

    /**
     * Edit form view
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nom'
                )
            )
            ->add('email')
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon'
                )
            )
            ->add(
                'birthyear',
                null,
                array(
                    'label' => 'Any naixement',
                    'required' => false,
                )
            )
            ->end()
            ->with('Adreça', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'postalCode',
                null,
                array(
                    'label' => 'Codi postal'
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Població'
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'active',
                null,
                array(
                    'label' => 'Actiu',
                    'required' => false
                )
            )
//            ->add(
//                'birthdate',
//                'sonata_type_date_picker',
//                array(
//                    'label'    => 'Data aniversari',
//                    'format'   => 'd/M/y',
//                    'required' => false,
//                )
//            )
            ->add(
                'groups',
                'sonata_type_model',
                array(
                    'label' => 'Grups',
                    'class' => 'LoPati\NewsletterBundle\Entity\NewsletterGroup',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'btn_add' => false,
                    'by_reference' => false,
                )
            )

            ->add(
                'idioma',
                'choice',
                array(
                    'choices'  => array('ca' => 'Català', 'es' => 'Castellano', 'en' => 'English'),
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
                'created',
                'date',
                array(
                    'label'    => 'Data alta',
                    'format'   => 'd/m/Y H:i',
                    'editable' => false,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label'    => 'Nom',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label'    => 'Email',
                    'editable' => true,
                )
            )
            ->add(
                'postalCode',
                null,
                array(
                    'label'    => 'Codi postal',
                    'editable' => true,
                )
            )
            ->add(
                'groups',
                null,
                array(
                    'label'    => 'Grups',
                    'editable' => false,
                )
            )
            ->add(
                'active',
                'boolean',
                array(
                    'label'    => 'Actiu',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => 'AdminBundle:Admin:list__action_edit_button.html.twig'),
                    ),
                    'label'   => 'Accions'
                )
            );
        ;
    }

    /**
     * Configure list view filters
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'created',
                'doctrine_orm_date',
                array(
                    'label'      => 'Data alta',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'Email',
                )
            )
            ->add(
                'postalCode',
                null,
                array(
                    'label' => 'Codi postal',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon',
                )
            )
            ->add(
                'birthyear',
                null,
                array(
                    'label' => 'Any naixement',
                )
            )
            ->add(
                'groups',
                null,
                array(
                    'label' => 'Grups',
                )
            )
            ->add(
                'active',
                null,
                array(
                    'label' => 'Actiu',
                )
            )
        ;
    }

    /**
     * Configure batch actions
     *
     * @return mixed
     */
    public function getBatchActions()
    {
        $actions['group'] = [
            'label'            => 'Assignar grup',
            'ask_confirmation' => false
        ];

        return $actions;
    }
}
