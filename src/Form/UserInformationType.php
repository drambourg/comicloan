<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Spider'],
                'label_attr' => ['class' => 'col-sm-12'],

            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Man'],
                'label_attr' => ['class' => 'col-sm-12'],

            ])
            ->add('age', IntegerType::class, [
                'required' => false,
                'label' => 'Pseudo',
                'attr' => ['placeholder' => '38'],
                'label_attr' => ['class' => 'col-sm-12'],

            ])
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
            ->add('avatarPicture', VichImageType::class, [
                'required' => false,
                'label' => 'Avatar',
                'label_attr' => ['class' => 'col-sm-12 custom-file'],
                'allow_delete' => false,
                'download_link' => false,
                'invalid_message' => 'File is too big'
            ])
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Old password',
                'label_attr' => ['class' => 'col-md-12'],
                'invalid_message' => 'Please enter your old password',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'options' => ['attr' => ['class' => 'password-field']],
                'mapped' => false,
                'label_attr' => ['class' => 'col-sm-12'],
                'constraints' => [
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
                'first_options' => ['label' => 'New password', 'label_attr' => ['class' => 'col-sm-12'],],
                'second_options' => ['label' => 'Repeat your new password', 'label_attr' => ['class' => 'col-sm-12'],]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
