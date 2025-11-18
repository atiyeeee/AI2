<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Choice;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['placeholder' => 'Enter city'],
                'constraints' => [
                    new NotBlank(['message' => 'City cannot be blank']),
                    new Length(['min' => 2, 'max' => 100]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Country',
                'choices' => [
                    'United States' => 'US',
                    'Canada' => 'CA',
                    'United Kingdom' => 'GB',
                    'Australia' => 'AU',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Poland' => 'PL',
                ],
                'constraints' => [
                    new Choice(['choices' => ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'PL']]),
                ],
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'attr' => ['placeholder' => 'Enter latitude', 'step' => '0.0000001'],
                'scale' => 7,
                'constraints' => [
                    new Range([
                        'min' => -90,
                        'max' => 90,
                        'notInRangeMessage' => 'Latitude must be between -90 and 90',
                    ]),
                ],
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'attr' => ['placeholder' => 'Enter longitude', 'step' => '0.0000001'],
                'scale' => 7,
                'constraints' => [
                    new Range([
                        'min' => -180,
                        'max' => 180,
                        'notInRangeMessage' => 'Longitude must be between -180 and 180',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
