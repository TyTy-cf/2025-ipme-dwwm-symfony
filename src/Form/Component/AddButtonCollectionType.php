<?php

declare(strict_types=1);

namespace App\Form\Component;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddButtonCollectionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('add', ButtonType::class, [
                'label' => '+',
                'attr' => [
                    'class' => 'btn btn-success',
                    'data-btn-selector' => $options['data-btn-selector'],
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data-btn-selector' => null,
        ]);
    }

}
