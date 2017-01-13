<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterUserAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Newsletter usuari';
    protected $baseRoutePattern = 'newsletter/user';
    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'email' // field name
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

    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
//            ->add('id')
            ->add('created', null, array('label' => 'Data alta', 'template' => 'AdminBundle:Admin:list_custom_created_datetime_field.html.twig'))
            ->addIdentifier('email')
            ->add('groups', null, array('label' => 'Grups'))
            ->add('idioma')
//            ->add('fail', null, array('label' => 'Enviaments erronis'))
            ->add('active', 'boolean', array('label' => 'Actiu', 'editable' => true))
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('email')
            ->add('city')
            ->add('postalCode')
            ->add('groups', null, array('label' => 'Grup'))
            ->add('idioma')
            ->add('created', 'doctrine_orm_date', array('label' => 'Data Alta'), null, array('widget' => 'single_text', 'required' => false,  'attr' => array('class' => 'datepicker')))
            ->add('active', null, array('label' => 'Actiu'));
    }

    public function getBatchActions()
    {
        $actions['group'] = [
            'label'            => 'Assignar grup',
            'ask_confirmation' => false
        ];

        return $actions;
    }
}
