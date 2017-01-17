<?php

namespace LoPati\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Service("lopati.form.newsletter_user")
 * @Tag("form.type", attributes = { "alias" = "mp_newsletter_user" })
 */

/**
 * Class NewsletterUserType
 *
 * @package LoPati\NewsletterBundle\Form
 */
class NewsletterUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.name',
                    'required' => false,
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'    => 'newsletter.form.email',
                    'required' => true,
                )
            )
            ->add(
                'postalCode',
                NumberType::class,
                array(
                    'label'    => 'newsletter.form.zip',
                    'required' => true,
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.phone',
                    'required' => false,
                )
            )
            ->add(
                'age',
                NumberType::class,
                array(
                    'label'    => 'newsletter.form.age',
                    'required' => false,
                    'mapped'   => false,
                )
            )
//            ->add(
//                'idioma',
//                ChoiceType::class,
//                array(
//                    'data'     => 'ca',
//                    'required' => false,
//                    'choices'  => array(
//                        'ca' => 'Català',
//                        'es' => 'Castellano',
//                        'en' => 'English'
//                    ),
//                )
//            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LoPati\NewsletterBundle\Entity\NewsletterUser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mp_newsletter_user';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'mp_newsletter_user';
    }
}
