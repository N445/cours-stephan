<?php

namespace App\Controller;

use App\Entity\Information;
use App\Form\Checkout\InformationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        if(!$this->getUser()->getInformation()){
            return $this->redirectToRoute('APP_PROFILE_INFORMATION_EDIT');
        }
        return $this->render('profile/informations.html.twig', [
        ]);
    }

    #[Route('/informations-edition', name: 'APP_PROFILE_INFORMATION_EDIT')]
    public function informationsEdit(Request $request, EntityManagerInterface $em): Response
    {
        if(!$information = $this->getUser()->getInformation()){
            $information = new Information();
            $this->getUser()->setInformation($information);
        }
        $form        = $this->createForm(InformationType::class, $information);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($this->getUser());
            $em->persist($information);
            $em->flush();
            $this->addFlash('success','Vos informations ont été modifiées');
            return $this->redirectToRoute('APP_PROFILE_INFORMATION');
        }

        return $this->render('profile/informations-edition.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/paiments', name: 'APP_PROFILE_PAYMENTS')]
    public function payments(): Response
    {
        return $this->render('profile/payments.html.twig', [
        ]);
    }
}
