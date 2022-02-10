<?php

namespace App\Form;

use App\Entity\Automobiles;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutomobilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',

                ]
            ])
            ->add('colors', TextType::class, [
                'label' => 'Couleurs',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Couleurs',

                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => 'App\Entity\Categories',
                'choice_label' => function (Categories $category) {
                    return $category->getName();
                }

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Automobiles::class,
        ]);
    }
}
