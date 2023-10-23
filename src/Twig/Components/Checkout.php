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

            if (dump($this->flow->nextStep())) {
                $form = $this->flow->createForm();
            } else {
                $this->flow->reset();
            }
        }

        dump($this->flow);

      return $form;
    }

    public function getCart(): ?Cart
    {
        return $this->initialFormData;
    }


    #[LiveAction]
    public function save(): void
    {

//        // Submit the form! If validation fails, an exception is thrown
//        // and the component is automatically re-rendered with the errors
//        $this->submitForm();
//
//        $post = $this->getForm()->getData();
//        $this->em->persist($post);
//        $this->em->flush();
//
//        $this->addFlash('success', 'Post saved!');
//
//        return $this->redirectToRoute('APP_CHECKOUT_RECAP');



//        // Submit the form! If validation fails, an exception is thrown
//        // and the component is automatically re-rendered with the errors
        $this->submitForm();
        $form = $this->getForm();
        /** @var Cart $cart */
        $cart = $form->getData();

        $this->flow->nextStep();

//
//        $this->flow->bind($cart);
//
//        if (!$form->isValid()) {
//            return;
//        }
//
//        $this->flow->saveCurrentStepData($form);
//
//        if ($this->flow->nextStep()) {
//            // form for the next step
//            $this->form     = $this->flow->createForm();
//            $this->formView = $this->form->createView();
//        } else {
//            $this->flow->reset();
//        }

        $this->em->persist($cart);
        $this->em->flush();
    }

    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
