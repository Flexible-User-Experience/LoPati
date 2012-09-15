<?php


namespace LoPati\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Product Admin
 */
class ProductAdmin extends Admin
{

	/**
	 * Configure the list
	 *
	 * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
	 */
	protected function configureListFields(ListMapper $list)
	{
		$list
		->add('id')
		->addIdentifier('title')
		->add('description', null, array('label' => 'Description'));
	}

	/**
	 * Configure the form
	 *
	 * @param FormMapper $formMapper formMapper
	 */
	public function configureFormFields(FormMapper $formMapper)
	{
       $formMapper
            ->add('title')
            ->add('description')
            ->add('prova')                      
            ->add('translations', 'translations',array(
                'by_reference' => false,
            		'required' => false,
            		'fields' => array(                          // [Optionnal] Fields configurations. If not, auto detection from translatable annotations
            				'title' => array(
            						'label' => 'name',                   // Custom label
            						'type' => 'textarea',
            						'attr' => array('class' => 'tinymce',
				 'data-theme'=>'bbcode',
				'style' => 'width: 600px; height: 400px;')               // Custom type : text or textarea. If not, auto detection from doctrine annotations
            				),
            				'description' => array(
            						'label' => 'description'
            				)
            		)
            
            		
            ));
	}
}