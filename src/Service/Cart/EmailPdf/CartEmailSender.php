<?php

namespace App\Service\Cart\EmailPdf;

use App\Entity\Cart\Cart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class CartEmailSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly CartToPdf       $cartToPdf,
    )
    {
    }

    public function sendMail(Cart $cart): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('contact@robin-parisot.fr', 'Cours Stéphan Robot'))
            ->to($cart->getUser()->getEmail())
            ->subject('Votre commande du ' . $cart->getPayedAt()->format('d/m/Y'))
            ->htmlTemplate('email-pdf/email/payment.html.twig')
            ->addPart(new DataPart(new File($this->cartToPdf->getPdf($cart)), sprintf('commande-%s.pdf', $cart->getPayedAt()->format('d-m-Y'))))
            ->context(
                [
                    'cart' => $cart,
                ],
            )
        ;

        $this->mailer->send($email);
    }
}