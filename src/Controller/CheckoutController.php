<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\AddressType;
use App\Service\Cart\CartProvider;
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
    )
    {
    }

    #[Route('/', name: 'APP_CHECKOUT_RECAP')]
    public function recap(Request $request): Response
    {
        return $this->render('checkout/recap.html.twig', [
            'cart' => $this->cartProvider->getUserCart(),
        ]);
    }

    #[Route('/adresse', name: 'APP_CHECKOUT_ADDRESS')]
    public function address(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$address = $user->getAddress()) {
            $address = (new User\Address())->setCountry('FR');
            $user->setAddress($address);
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
        return $this->render('checkout/payment-method.html.twig', [
            'cart' => $this->cartProvider->getUserCart(),
        ]);
    }
}
