<?php

namespace App\Controller;

use App\Repository\Module\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository,
    )
    {
    }

    #[Route('/', name: 'APP_HOMEPAGE')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'modules' => $this->moduleRepository->findAll(),
        ]);
    }
}
