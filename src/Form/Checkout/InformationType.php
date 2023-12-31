<?php

namespace App\Form\Checkout;

use App\Entity\Cart\Cart;
use App\Entity\Information;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('address1', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('address2', TextType::class, [
                'label'    => 'Adresse 2',
                'required' => false,
            ])
            ->add('address3', TextType::class, [
                'label'    => 'Adresse 3',
                'required' => false,
            ])
            ->add('postCode', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
            ])
//            ->add('country', CountryType::class, [
//                'label' => 'Pays',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Information::class,
            ],
        );
    }
}