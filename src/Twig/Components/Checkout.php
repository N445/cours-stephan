<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Form\Checkout\CheckoutFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class Checkout extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public Cart|null $initialFormData = null;

    public function __construct(
        public CheckoutFlow                     $flow,
        private readonly EntityManagerInterface $em,
        private readonly RequestStack           $requestStack,
    )
    {
    }


    protected function instantiateForm(): FormInterface
    {
        $this->flow->bind($this->initialFormData);

        // form of the current step
        $form = $this->flow->createForm();
        if ($this->flow->isValid($form)) {
            $this->flow->saveCurrentStepData($form);

            if ($this->flow->nextStep()) {
                $form = $this->flow->createForm();
            } else {
                $this->flow->reset();
            }
        }

        return $form;
    }

    public function getCart(): ?Cart
    {
        return $this->initialFormData;
    }
}
