<?php

namespace App\Form\Checkout;

use Craue\FormFlowBundle\Form\FormFlow;

class CheckoutFlow extends FormFlow
{
    protected function loadStepsConfig(): array
    {
        return [
            [
                'label'     => 'Panier',
                'form_type' => RecapType::class,
            ],
            [
                'label'     => 'Informations',
                'form_type' => CartInformationType::class,
            ],
            [
                'label'     => 'Mode de réglement',
                'form_type' => MethodPaymentType::class,
            ],
            [
                'label' => 'Confirmation',
            ],
        ];
    }

}