<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/paiement', name: 'APP_CHECKOUT')]
    public function checkout(Request $request): Response
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
        }

        return $this->render('checkout/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
