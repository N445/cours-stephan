<?php

namespace App\Controller;

use App\Repository\Module\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository,
    )
    {
    }

    #[Route('/checkout/{moduleId}', name: 'APP_CHECKOUT')]
    public function index(int $moduleId): Response
    {
        if(!$module = $this->moduleRepository->find($moduleId)){
            return $this->redirectToRoute('APP_MODULES');
        }
        return $this->render('checkout/index.html.twig', [
            'module' => $module,
        ]);
    }
}
