<?php

namespace LoPati\AdminBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Class ArxiuAdmin
 *
 * @category Admin
 * @package  LoPati\AdminBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ArxiuAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Arxiu';
    protected $baseRoutePattern = 'archive';
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'any' // field name
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add('any', 'integer', array('label' => 'Any'))
            ->add('imagePetita', 'file', array('label' => 'Imatge any', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('ImagePetitaName', 'imagePetita')))
            ->add('imagePetita2', 'file', array('label' => 'Imatge any', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail('ImagePetita2Name', 'imagePetita2')))
//            ->add('imagePetita2', 'file', array('label' => 'Imatge any vermell', 'required' => false, 'help' => $this->getImageHelperFormMapperWithThumbnail()))
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add('actiu', 'checkbox', array('required' => false))
            ->end()
            ->setHelps(array('any' => 'Ex: 2012'));
    }

    /**
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        unset($this->listModes['mosaic']);
        $mapper
            ->add('any', null, array('editable' => true))
            ->add(
                'imagePetitaName',
                null,
                array('label' => 'Imatge', 'template' => 'AdminBundle:Admin:customarxiuimglistfield.html.twig')
            )
            ->add(
                'imagePetita2Name',
                null,
                array('label' => 'Imatge vermell', 'template' => 'AdminBundle:Admin:customarxiuredimglistfield.html.twig')
            )
            ->add('actiu', 'boolean', array('editable' => true))
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
    }
}
