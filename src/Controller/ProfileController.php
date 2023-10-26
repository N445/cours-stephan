<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/profile")]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'APP_PROFILE')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
        ]);
    }
    #[Route('/informations', name: 'APP_PROFILE_INFORMATION')]
    public function informations(): Response
    {
        return $this->render('profile/informations.html.twig', [
        ]);
    }
    #[Route('/paiments', name: 'APP_PROFILE_PAYMENTS')]
    public function payments(): Response
    {
        return $this->render('profile/payments.html.twig', [
        ]);
    }
}
