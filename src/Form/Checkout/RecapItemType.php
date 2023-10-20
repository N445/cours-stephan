<?php

namespace App\Form\Checkout;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Form\Cart\RecapCartItemType;
use App\Service\Cart\CartItemLocationHelper;
use Craue\FormFlowBundle\Form\FormFlow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecapItemType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Nombre de participant',
            ])
            ->add('location', ChoiceType::class, [
                'label'    => 'Lieu',
                'expanded' => true,
                'choices'  => array_flip(CartItemLocationHelper::getLocations()),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => CartItem::class,
                               ]);
    }
}