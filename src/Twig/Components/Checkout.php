<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Form\Checkout\CheckoutFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
        return $this->flow->createForm();
    }

    public function getCart(): ?Cart
    {
        return $this->initialFormData;
    }


    #[LiveAction]
    public function save(): void
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();
        $form = $this->getForm();
        $form->handleRequest($this->requestStack->getMainRequest());
        /** @var Cart $cart */
        $cart = $form->getData();

        $this->flow->bind($cart);


        if (!$form->isValid()) {
            return;
        }

        $this->flow->saveCurrentStepData($form);

        if (dump($this->flow->nextStep())) {
            // form for the next step
            $this->form     = $this->flow->createForm();
            $this->formView = $this->form->createView();
        } else {
            $this->flow->reset();
        }

        $this->em->persist($cart);
        $this->em->flush();
        $this->initialFormData = $cart;
    }
}
