<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Cart\CartMethodPaymentType;
use App\Form\Cart\RecapCartType;
use App\Form\User\AddressType;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleOccurenceCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
#[Route('/paiement')]
class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CartProvider           $cartProvider,
        private readonly ModuleOccurenceCounter $moduleOccurenceCounter,
    )
    {
    }

    #[Route('/', name: 'APP_CHECKOUT_RECAP')]
    public function recap(Request $request): Response
    {
        $cart = $this->cartProvider->getUserCart();
        if ($cart) {
            $nbOccurenceByCartItem = [];
            foreach ($cart->getCartItems() as $cartItem) {
                $nbOccurenceByCartItem[$cartItem->getId()] = $this->moduleOccurenceCounter
                    ->getNbOccurenceBySchedule(
                        $cartItem->getSchedule(),
                        $cartItem->getOccurenceId(),
                    )
                ;
            }
        }

        // we can extend AbstractController to get the normal shortcuts
        $form = $this->createForm(RecapCartType::class, $cart);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($cart);
            $this->em->flush();
            return $this->redirectToRoute('APP_CHECKOUT_ADDRESS');
        }

        return $this->render('checkout/recap.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/adresse', name: 'APP_CHECKOUT_ADDRESS')]
    public function address(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$address = $user->getInformation()) {
            $address = (new \App\Entity\Information())->setCountry('FR');
            $user->setInformation($address);
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('APP_CHECKOUT_PAYMENT_METHOD');
        }

        return $this->render('checkout/address.html.twig', [
            'form' => $form->createView(),
            'cart' => $this->cartProvider->getUserCart(),
        ]);
    }

    #[Route('/mode-de-paiement', name: 'APP_CHECKOUT_PAYMENT_METHOD')]
    public function paymentMethod(Request $request): Response
    {
        $cart = $this->cartProvider->getUserCart();
        $form = $this->createForm(CartMethodPaymentType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($cart);
            $this->em->flush();
            return $this->redirectToRoute('PAYMENT_PREPARE');
        }


        return $this->render('checkout/payment-method.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }
}
