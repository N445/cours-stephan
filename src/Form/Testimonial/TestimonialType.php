<?php

namespace App\Form\Testimonial;

use App\Entity\Testimonial\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
//            ->add('note', NumberType::class, [
//                'label' => 'Note',
//            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => Testimonial::class,
                               ]);
    }
}
