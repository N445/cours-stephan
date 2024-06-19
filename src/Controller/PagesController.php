<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Testimonial\Testimonial;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\Testimonial\TestimonialType;
use App\Repository\Testimonial\TestimonialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagesController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TestimonialRepository  $testimonialRepository,
    )
    {
    }

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
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user    = $this->getUser();
        $contact = (new Contact());
        if ($user) {
            $contact->setEmail($user?->getEmail());
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Nous avons bien reçu votre message.');
            return $this->redirectToRoute('APP_CONTACT');
        }
        return $this->render('pages/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/faq', name: 'APP_FAQ')]
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig', [
        ]);
    }

    #[Route('/temoignage', name: 'APP_TESTIMONIAL')]
    public function testimonial(Request $request): Response
    {
        $testimonial = new Testimonial();
        $form        = $this->createForm(TestimonialType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($testimonial);
            $this->em->flush();
            $this->addFlash('success', 'Nous avons bien reçu votre temoignage');
            return $this->redirectToRoute('APP_TESTIMONIAL');
        }

        return $this->render('pages/testimonial.html.twig', [
            'form'         => $form->createView(),
            'testimonials' => $this->testimonialRepository->findAllForFront(),
        ]);
    }
}
