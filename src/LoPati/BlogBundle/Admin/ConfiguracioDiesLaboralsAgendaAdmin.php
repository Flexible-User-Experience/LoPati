<?php
namespace LoPati\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;


class ConfiguracioDiesLaboralsAgendaAdmin extends Admin
{
    /**
     * Configure export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ->remove('edit');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('id', null, array('required' => true));
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('id')
            ->add('name', null, array('label' => 'Dia'))
            ->add('active', null, array('label' => 'És laboral?', 'editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        //'view' => array(),
                        'edit'   => array(),
                        'delete' => array(),
                    ),
                    'label'   => 'Accions'
                )
            );
    }

}	