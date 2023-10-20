<?php

namespace App\Form\Checkout;

use App\Entity\Cart\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MethodPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('methodPayment', ChoiceType::class, [
                'label'    => 'Mode de réglement',
                'expanded' => true,
                'choices'  => [
                    'Hors ligne' => 'offline',
                    'Paypal'     => 'paypal',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => Cart::class,
                               ]);
    }
}
