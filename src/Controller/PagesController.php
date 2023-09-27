<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/professeur', name: 'APP_PROFESSOR')]
    public function professeur(): Response
    {
        return $this->render('pages/professeur.html.twig', [
        ]);
    }
    #[Route('/tarif-fonctionnement', name: 'APP_TARIF_FONCTIONNEMENT')]
    public function tarif_fonctionnement(): Response
    {
        return $this->render('pages/tarif_fonctionnement.html.twig', [
        ]);
    }
    #[Route('/contact', name: 'APP_CONTACT')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig', [
        ]);
    }
}
