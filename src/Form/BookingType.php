<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Service;
use App\Validator\AvailableTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réservation'
            ])
            ->add('time', TimeType::class, [
                'constraints' => [
                    new AvailableTime(),
                ],
                'widget' => 'single_text',
                'label' => 'Heure de réservation',
                'input' => 'datetime',
                'mapped' => false,
                'attr' => [
                'class' => 'timepicker',
        ],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
                'label' => 'Type de service',
                'attr' => ['class' => 'form-control']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
