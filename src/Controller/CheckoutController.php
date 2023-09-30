<?php

namespace App\Controller;

use App\Entity\Module\Module;
use App\Repository\Module\ModuleRepository;
use App\Repository\Module\SheduleRepository;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleRRuleProvider;
use RRule\RRule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository    $moduleRepository,
        private readonly SheduleRepository   $sheduleRepository,
        private readonly ModuleRRuleProvider $moduleRRuleProvider,
        private readonly CartProvider        $cartProvider,
    )
    {
    }

    #[Route('/programmes', name: 'APP_SHEDULES')]
    public function plannings(): Response
    {
        return $this->render('checkout/plannings.html.twig', [
            'shedules' => $this->sheduleRepository->findAll(),
        ]);
    }

    #[Route('/programme/{sheduleId}', name: 'APP_SHEDULE')]
    public function planning(int $sheduleId): Response
    {
        if (!$shedule = $this->sheduleRepository->find($sheduleId)) {
            return $this->redirectToRoute('APP_PLANNINGS');
        }

        $modules = $this->moduleRepository->findAll();

        dump($this->cartProvider->getUserCart());

        return $this->render('checkout/planning.html.twig', [
            'shedule' => $shedule,
            'events'  => $this->moduleRRuleProvider->getModulesRRulesDates($shedule, $modules),
        ]);
    }
}
