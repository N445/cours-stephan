<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Form\Checkout\CheckoutFlow;
use App\Repository\Cart\CartItemRepository;
use App\Service\Cart\CartHelper;
use App\Service\Cart\CartProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
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
        private readonly CartItemRepository     $cartItemRepository,
        private readonly CartProvider           $cartProvider,
        private readonly CartHelper             $cartHelper,
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

    /**
     * @throws \Exception
     */
    #[LiveAction]
    public function removeCartItem(#[LiveArg('cartItem')] CartItem $cartItemId): void
    {
        if (!$cartItem = $this->cartItemRepository->find($cartItemId)) {
            return;
        }
        $this->cartHelper->removeCartItemFromCart($this->initialFormData, $cartItem);
        $this->initialFormData->sortCartItems();
    }
}
