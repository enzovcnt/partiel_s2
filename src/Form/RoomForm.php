<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', ChoiceType::class, [
                    'choices' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'label' => 'Numéro de salle'])
            ->add('seats', ChoiceType::class, [
                'choices' => [
                    '65' => '65',
                    '85' => '85',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
