<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;
use App\Entity\Measurement;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('celsius', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'The value {{ value }} is not a valid number.',
                    ]),
                    new Range([
                        'min' => -40,
                        'max' => 60,
                        'minMessage' => 'The value must be greater than or equal to {{ limit }}.',
                        'maxMessage' => 'The value must be less than or equal to {{ limit }}.',
                    ]),
                ],
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city',
                'placeholder' => 'Select a location',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if ($data->getId() !== null) {
                    // Grupa walidacyjna 'edit' dla edycji pomiaru
                    return ['Default', 'edit'];
                }

                // Grupa walidacyjna 'new' dla tworzenia nowego pomiaru
                return ['Default', 'new'];
            },
        ]);
    }
}
