<?php

namespace LoPati\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use PcSubasta\AppBundle\Entity\Image;

/**
 * @Service("lo_pati.form.pati_image")
 * @Tag("form.type", attributes = { "alias" = "lo_pati_image" })
 */
class ImageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('file', 'file', array(
                    'required' => false,
                    'label' => 'Imagen'
                ))
        ;
    }
    
 public function buildView(FormView $view,  FormInterface $form)
    {
    	$file = $form->getData();
    
    	if ($file instanceof Image) {
    		$view->set('file', $file);
    	}
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'LoPati\BlogBundle\Entity\Image'
    	));
    }
    public function getName()
    {
        return 'lo_pati_image';
    }
}
