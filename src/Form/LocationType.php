<?php

// src/Form/LocationType.php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'City',
                'constraints' => [
                    new NotBlank([
                        'groups' => ['create', 'edit'],
                    ]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Country',
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United Kingdom' => 'GB',
                    'United States' => 'US',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => ['create', 'edit'],
                    ]),
                ],
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'constraints' => [
                    new NotBlank([
                        'groups' => ['create', 'edit'],
                    ]),
                    new Range([
                        'min' => -90,
                        'max' => 90,
                        'groups' => ['create', 'edit'],
                    ]),
                ],
            ])
            ->add('langitude', TextType::class, [
                'label' => 'Langitude',
                'constraints' => [
                    new NotBlank([
                        'groups' => ['create', 'edit'],
                    ]),
                    new Range([
                        'min' => -90,
                        'max' => 90,
                        'groups' => ['create', 'edit'],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'validation_groups' => ['create'],
        ]);
    }
}
