<?php

namespace LoPati\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service("lopati.form.configuration_translation")
 * @Tag("form.type", attributes = { "alias" = "mp_configuration_translation" })
 */
class ConfigurationTranslationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('lang', 'hidden')
                ->add('privacyPolice', 'textarea', array(
                    'required' => false,
                    'label' => 'Politica privada',
                    'attr' => array(
                        'class' => 'wysiwyg'
                    )
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LoPati\BlogBundle\Entity\ConfigurationTranslation',
        ));
    }
    public function getDefaultOptions(array $options)
    {
    	return array(
    			'data_class' => 'Loati\BlogBundle\Entity\ConfigurationTranslation'
    	);
    }
    public function getName()
    {
        return 'mp_configuration_translation';
    }

}
