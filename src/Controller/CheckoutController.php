<?php

namespace App\Controller;

use App\Repository\Module\ModuleRepository;
use App\Repository\Module\PlanningRepository;
use RRule\RRule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository   $moduleRepository,
        private readonly PlanningRepository $planningRepository
    )
    {
    }

    #[Route('/plannings', name: 'APP_PLANNINGS')]
    public function plannings(): Response
    {
        return $this->render('checkout/plannings.html.twig', [
            'plannings' => $this->planningRepository->findAll()
        ]);
    }

    #[Route('/planning/{planningId}', name: 'APP_PLANNING')]
    public function planning(int $planningId): Response
    {
        if (!$planning = $this->planningRepository->find($planningId)) {
            return $this->redirectToRoute('APP_PLANNINGS');
        }

        $events = [];
        $modules = $this->moduleRepository->findAll();

        foreach ($modules as $module) {
            foreach ($module->getDays() as $day) {
                $rrule = new RRule([
                    'BYDAY' => $day,
                    'FREQ' => RRule::WEEKLY,
                    'INTERVAL' => 1,
                    'DTSTART' => $planning->getStartAt()->format('Y-m-d'),
                    'UNTIL' => $planning->getEndAt()->format('Y-m-d'),
                ]);

                /** @var \DateTime $occurrence */
                foreach ($rrule as $occurrence) {
                    foreach ($module->getHours() as $hour) {
                        $startAt = (clone $occurrence)->add(new \DateInterval($hour));
                        $endAt = (clone $startAt)->add(new \DateInterval('PT1H30M'));

                        $events[] = [
                            "title" => $module->getName(),
                            "start" => $startAt->format(DATE_ATOM),
                            "end" => $endAt->format(DATE_ATOM)
                        ];
                    }
                }
            }
        }

        dump($events);

        return $this->render('checkout/planning.html.twig', [
            'planning' => $planning,
            'events' => $events,
        ]);
    }

    #[Route('/checkout/{moduleId}', name: 'APP_CHECKOUT')]
    public function checkout(int $moduleId): Response
    {
        if (!$module = $this->moduleRepository->find($moduleId)) {
            return $this->redirectToRoute('APP_MODULES');
        }
        return $this->render('checkout/index.html.twig', [
            'module' => $module,
        ]);
    }
}
