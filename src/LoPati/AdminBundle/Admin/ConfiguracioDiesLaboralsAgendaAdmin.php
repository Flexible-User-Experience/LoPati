<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConfiguracioDiesLaboralsAgendaAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Configuració dies laborals agenda';
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

    /**
     * @param RouteCollection $collection
     */
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

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('id', null, array('required' => true))
            ->end();
    }

    /**
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add('id')
            ->add('name', null, array('label' => 'Dia'))
            ->add('active', null, array('label' => 'És laboral?', 'editable' => true));
    }
}
