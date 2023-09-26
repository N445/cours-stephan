<?php

namespace App\Controller;

use App\Repository\Module\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository,
    )
    {
    }

    #[Route('/modules', name: 'APP_MODULES')]
    public function index(): Response
    {
        return $this->render('module/index.html.twig', [
            'modules' => $this->moduleRepository->findAll(),
        ]);
    }
}
