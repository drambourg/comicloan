<?php

namespace App\Form;

use App\Entity\RequestComicLoan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestSubmitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comicId', HiddenType::class)
            ->add('dateAt', HiddenType::class)
            ->add('message', TextareaType::class, [
                'label' => 'Your help message :',
                'label_attr' => [
                    'class' => 'col-sm-12'
                    ],
                'attr' => [
                    'placeholder' => 'Help me to have it!! Please!',
                    'rows' => 5,
                ],
                'required' => false,
            ])
            ->add('status', HiddenType::class)
            ->add('user',HiddenType::class)
            ->add('response',HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestComicLoan::class,
        ]);
    }
}
