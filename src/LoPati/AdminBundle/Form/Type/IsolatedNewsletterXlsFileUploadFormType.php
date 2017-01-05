<?php

namespace LoPati\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IsolatedNewsletterXlsFileUploadFormType
 */
class IsolatedNewsletterXlsFileUploadFormType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                FileType::class,
                array(
                    'label'    => 'Arxiu',
                    'required' => true,
                    'attr'     => array(
                        'class' => 'form-control',
                    ),
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'Enviar',
                    'attr'  => array(
                        'class' => 'btn btn-primary',
                        'style' => 'margin-top:5px',
                    )
                )
            )
        ;
    }

    /**
     * Returns the name of this type
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'file_upload_form_type';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => true,
            )
        );
    }
}
