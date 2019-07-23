<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudoname', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'MagicCasey'],
                'label_attr' => ['class' => 'col-sm-12'],

            ])
            ->add('country', CountryType::class, [
                'required' => true,
                'placeholder' => 'Select a country',
                'label_attr' => ['class' => 'col-sm-12'],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Springfield'],
                'label_attr' => ['class' => 'col-sm-12'],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'captain.america@avengers.com'],
                'label_attr' => ['class' => 'col-sm-12'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr' => ['class' => 'password-field']],
                'mapped' => false,
                'label_attr' => ['class' => 'col-sm-12'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    /*                    new Expression([
                                            'expression' => "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/",
                                            'message' => "The password needs contain One specialCharacter, uppercase and lowercase."
                                        ]),*/
                ],
                'first_options' => ['label' => 'Enter your password', 'label_attr' => ['class' => 'col-sm-12'],],
                'second_options' => ['label' => 'Repeat your password', 'label_attr' => ['class' => 'col-sm-12'],]
            ])
            ->add('dateCreated', DateTimeType::class, [
                'data' => new \DateTime(),
                'attr' => ['hidden' => true],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
