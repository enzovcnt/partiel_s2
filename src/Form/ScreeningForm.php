<?php

namespace App\Form;

use App\Entity\Dub;
use App\Entity\Film;
use App\Entity\Room;
use App\Entity\Schedule;
use App\Entity\Screening;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScreeningForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price')
            ->add('film', EntityType::class, [
                'class' => Film::class,
                'choice_label' => 'name',
            ])
            ->add('dub', EntityType::class, [
                'class' => Dub::class,
                'choice_label' => 'version',
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'number',
            ])
            ->add('schedule', EntityType::class, [
                'class' => Schedule::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Screening::class,
        ]);
    }
}
