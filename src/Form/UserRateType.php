<?php

namespace App\Form;

use App\Entity\UserRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', NumberType::class,[
                'label_attr' => ['class' => 'text-white text-center col-sm-6'],
                    'attr' => ['class' => 'col-lg-2 text-center mx-auto mb-3'],
                'label' => 'Rate Hero Between 1 and 5',
                    'data' => 1
                ]
            )
            ->add('comment',TextareaType::class, [
                'label' => 'Let a testimony',
                'label_attr' => ['class' => 'text-white']
            ])
            ->add('dateAt', HiddenType::class)
            ->add('user', HiddenType::class)
            ->add('author', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRate::class,
        ]);
    }
}
