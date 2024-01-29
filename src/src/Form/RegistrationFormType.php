<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un nom',
                ]),
                new Length([
                    'max' => 255,
                    'maxMessage' => 'Le nom ne peut pas dépasser 255 caractères',
                ]),
            ],
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un prénom',
                ]),
                new Length([
                    'max' => 255,
                    'maxMessage' => 'Le prénom ne peut pas dépasser 255 caractères',
                ]),
            ],
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un numéro de téléphone',
                ]),
                // Vous pouvez ajouter d'autres contraintes spécifiques aux numéros de téléphone
            ],
        ])
        ->add('classe', TextType::class, [
            'label' => 'Classe',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer une classe',
                ]),
                new Length([
                    'max' => 255,
                    'maxMessage' => 'La classe ne peut pas dépasser 255 caractères',
                ]),
            ],
        ])
        ->add('email')
        ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
