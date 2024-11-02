<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'adresse email ne peut pas être vide.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse email n\'est pas un email valide.',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'maxlength' => 10,
                    'placeholder' => 'Téléphone (10 chiffres)',
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 10,
                        'max' => 10,
                        'exactMessage' => 'Le numéro de téléphone doit contenir exactement {{ limit }} chiffres.'
                    ]),
                    new Assert\Regex(['pattern' => '/^\d{10}$/', 'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres.']),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
