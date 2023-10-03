<?php

namespace App\Controller\Payment;

use App\Entity\Payum\Payment;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/paiement/paypal")]
class PaypalPayment extends AbstractController
{
    public function __construct(
        private readonly Payum $payum
    )
    {
    }

    #[Route("/prepare", name: "PAYMENT_PAYPAL_PREPARE")]
    public function prepare(): Response
    {
        $gatewayName = 'offline';
        $gateway = $this->payum->getGateway($gatewayName);
        $storage = $this->payum->getStorage(Payment::class);

        /** @var Payment $details */
        $details = $storage->create();

        $details->setNumber(uniqid('', true));
        $details->setCurrencyCode('EUR');
        $details->setTotalAmount(20000);
        $details->setDescription('sdsdfsdf');
        $details->setClientEmail("sdfsfsfs");
        $storage->update($details);

        $captureToken = $this->payum->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $details,
            'PAYMENT_PAYPAL_SUCCESS' // the route to redirect after capture;
        );


        dump($this->payum);
        dump($storage);
        dump($details);
        return $this->redirect($captureToken->getTargetUrl());
        die;
    }

    #[Route("/success", name: "PAYMENT_PAYPAL_SUCCESS")]
    public function success(Request $request): Response
    {
        $token = $this->payum->getHttpRequestVerifier()->verify($request);

        dump($token);

        $gateway = $this->payum->getGateway($token->getGatewayName());
        dump($gateway);

        // You can invalidate the token, so that the URL cannot be requested any more:
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        // Once you have the token, you can get the payment entity from the storage directly.
        // $identity = $token->getDetails();
        // $payment = $this->get('payum')->getStorage($identity->getClass())->find($identity);

        // Or Payum can fetch the entity for you while executing a request (preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        /** @var Payment $payment */
        $payment = $status->getFirstModel();
        dump($payment);
        dump($status);
        dump($status->isCaptured());
        dump($status->isCanceled());
        dump($status->isFailed());
        dump($status->isPending());
        dump($status->isAuthorized());

        die;
    }
}