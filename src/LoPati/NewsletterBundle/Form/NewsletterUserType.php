<?php

namespace LoPati\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.name',
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'    => 'newsletter.form.email',
                    'required' => true,
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.email',
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email(
                            array(
                                'checkMX' => true,
                            )
                        ),
                    ),
                )
            )
            ->add(
                'postalCode',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.zip',
                    'required' => true,
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.zip',
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Regex('/^\d/'),
                        new Assert\Length(
                            array(
                                'min' => 5,
                                'max' => 5,
                            )
                        ),
                    ),
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label'    => 'newsletter.form.phone',
                    'required' => false,
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.phone',
                    ),
                )
            )
            ->add(
                'age',
                NumberType::class,
                array(
                    'label'    => 'newsletter.form.age',
                    'required' => false,
                    'attr'     => array(
                        'placeholder' => 'newsletter.form.age',
                    ),
                    'constraints' => array(
                        new Assert\GreaterThan(0),
                    ),
                )
            )
            ->add(
                'privacy_policy',
                CheckboxType::class,
                array(
                    'mapped' => false,
                    'label' => 'Confirm the privacy policy',
                    'required' => true,
                )
            )
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
