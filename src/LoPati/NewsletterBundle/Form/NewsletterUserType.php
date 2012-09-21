<?php

namespace LoPati\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service("lopati.form.newsletter_user")
 * @Tag("form.type", attributes = { "alias" = "mp_newsletter_user" })
 */
class NewsletterUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email')
                ->add('idioma','choice', array( 'choices'   => array( 'ca' => 'Català','es' => 'Castellano','en'=>'English'), 'required'  => true,))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LoPati\NewsletterBundle\Entity\NewsletterUser'
        ));
    }

    public function getName()
    {
        return 'mp_newsletter_user';
    }

}
