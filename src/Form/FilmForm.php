<?php

namespace App\Form;

//mettre l'entité à utiliser ici > use App/
use App\Entity\Category;
use App\Entity\Dub;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
