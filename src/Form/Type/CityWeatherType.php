<?php

namespace App\Form\Type;

use App\Form\DTO\CityWeatherDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityWeatherType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        $builder
            ->add(
                'city',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-city',
                    ],
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CityWeatherDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new CityWeatherDTO(
                    $form->get('city')->getData()
                );
            },
            'translation_domain' => 'forms',
        ]);
    }
}
