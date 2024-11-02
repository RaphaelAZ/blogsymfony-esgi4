<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réservation',
                'attr' => ['class' => 'form-control']
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de réservation',
                'attr' => ['class' => 'form-control']
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
