<?php

namespace App\Form;


use App\Entity\Category;
use App\Entity\Dub;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('summary')
            ->add('dub', EntityType::class, [
                'class' => Dub::class,
                'choice_label' => 'version',
            ])
        ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => 'name',
    ])
            ->add('duration', DateIntervalType::class, [
                'input'  => 'dateinterval',
                'widget' => 'choice',
                'with_hours' => true,
                'with_minutes' => true,
                'with_days' => false,
                'with_months' => false,
                'with_years' => false,
                'with_seconds' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
