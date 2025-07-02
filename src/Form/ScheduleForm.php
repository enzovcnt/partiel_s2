<?php

namespace App\Form;

use App\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('days', ChoiceType::class, [
//                'choices' => [
//                    'Lundi' => 'lundi',
//                    'Mardi' => 'mardi',
//                    'Mercredi' => 'mercredi',
//                    'Jeudi' => 'jeudi',
//                    'Vendredi' => 'vendredi',
//                    'Samedi' => 'samedi',
//                    'Dimanche' => 'dimanche',
//                ],
//            ])
            ->add('hours', ChoiceType::class, [
                'choices' => [
                    '10:30' => new \DateTime('10:30'),
                    '13:45' => new \DateTime('13:45'),
                    '16:30' => new \DateTime('16:30'),
                    '19:00' => new \DateTime('19:00'),
                    '22:15' => new \DateTime('22:15'),
                ],
                'label' => 'Heure de la séance',
            ])
        ->add('date', DateType::class, [
        'widget' => 'single_text',
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
