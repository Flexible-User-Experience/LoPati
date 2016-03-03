<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConfiguracioDiesLaboralsAgendaAdmin extends Admin
{
    protected $baseRoutePattern = 'configuration/calendar';

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
            ->remove('batch')
            ->remove('export')
            ->remove('show')
            ->remove('create')
            ->remove('delete')
            ->remove('edit');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('id', null, array('required' => true))
            ->end();
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('id')
            ->add('name', null, array('label' => 'Dia'))
            ->add('active', null, array('label' => 'És laboral?', 'editable' => true));
    }

}	