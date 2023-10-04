<?php

namespace App\Controller\Payment;

use App\Entity\Payum\Payment;
use App\Entity\User;
use App\Service\Cart\CartHelper;
use App\Service\Cart\CartProvider;
use Doctrine\ORM\EntityManagerInterface;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route("/paiement/")]
#[IsGranted("ROLE_USER")]
class PaymentController extends AbstractController
{
    public function __construct(
        private readonly Payum                  $payum,
        private readonly CartProvider           $cartProvider,
        private readonly CartHelper             $cartHelper,
        private readonly WorkflowInterface      $cartStateMachine,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route("/prepare", name: "PAYMENT_PREPARE")]
    public function prepare(): Response
    {
        $cart = $this->cartProvider->getUserCart();
        if (!$this->cartStateMachine->can($cart, 'to_pending')) {
            $this->addFlash('error', 'Panier non valide cccc');
            return $this->redirectToRoute('APP_HOMEPAGE');
        }
        $this->cartStateMachine->apply($cart, 'to_pending');
        $this->em->persist($cart);
        $this->em->flush();


        /** @var User $user */
        $user        = $this->getUser();
        $gatewayName = $cart->getMethodPayment();

        $gateway = $this->payum->getGateway($gatewayName);
        $storage = $this->payum->getStorage(Payment::class);

        /** @var Payment $details */
        $details = $storage->create();

        $details->setNumber(uniqid('', true));
        $details->setCurrencyCode('EUR');
        $details->setTotalAmount($this->cartHelper->getTotalCart($cart));
//        $details->setDescription('sdsdfsdf');
        $details->setClientEmail($user->getEmail());
        $storage->update($details);

        $captureToken = $this->payum->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $details,
            'PAYMENT_SUCCESS', // the route to redirect after capture;
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    #[Route("/success", name: "PAYMENT_SUCCESS")]
    public function success(Request $request): Response
    {
        $token = $this->payum->getHttpRequestVerifier()->verify($request);
        $gateway = $this->payum->getGateway($token->getGatewayName());

        // Or Payum can fetch the entity for you while executing a request (preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        /** @var Payment $payment */
        $payment = $status->getFirstModel();


        $cart = $this->cartProvider->getUserCart();
        if($status->isCaptured()){
            if (!$this->cartStateMachine->can($cart, 'to_complete')) {
                $this->addFlash('error', 'Panier non valide bbbbb');
                return $this->redirectToRoute('APP_HOMEPAGE');
            }
            $this->cartStateMachine->apply($cart, 'to_complete');
            $this->em->persist($cart);
            $this->em->flush();
            $this->addFlash('success', 'Panier validé');
            return $this->redirectToRoute('APP_HOMEPAGE');
        }


        if($status->isCanceled()){
            if (!$this->cartStateMachine->can($cart, 'to_cancelled')) {
                $this->addFlash('error', 'Panier non valide aaaa');
                return $this->redirectToRoute('APP_HOMEPAGE');
            }
            $this->cartStateMachine->apply($cart, 'to_cancelled');
            $this->em->persist($cart);
            $this->em->flush();
            $this->addFlash('error', 'Panier annulé');
            return $this->redirectToRoute('APP_HOMEPAGE');
        }


        dump($payment);
        dump($status);
        dump($status->isCaptured()); // ok
        dump($status->isCanceled()); // pas ok


        dump($status->isFailed());
        dump($status->isPending());
        dump($status->isAuthorized());

        $this->addFlash('info', 'Rien');
        return $this->redirectToRoute('APP_HOMEPAGE');
    }
}