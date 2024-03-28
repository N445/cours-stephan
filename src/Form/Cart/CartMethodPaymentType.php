<?php

namespace App\Form\Cart;

use App\Entity\Cart\Cart;
use App\Service\Helper\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartMethodPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('methodPayment', ChoiceType::class, [
                'label'    => 'Mode de réglement',
                'expanded' => true,
                'choices'  => [
                    'Virement'                 => PaymentMethod::VIREMENT,
                    'Paypal ou carte bancaire' => PaymentMethod::PAYPAL,
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
