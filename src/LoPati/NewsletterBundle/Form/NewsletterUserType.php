<?php

namespace LoPati\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.name',
                    ),
                    'required' => false,
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'    => 'newsletter.form.email',
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.email',
                    ),
                    'required' => true,
                )
            )
            ->add(
                'postalCode',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.zip',
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.zip',
                    ),
                    'required' => true,
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.phone',
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.phone',
                    ),
                    'required' => false,
                )
            )
            ->add(
                'age',
                NumberType::class,
                array(
                    'label'    => 'newsletter.form.age',
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.age',
                    ),
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
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label'    => 'newsletter.form.send',
                )
            )
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
