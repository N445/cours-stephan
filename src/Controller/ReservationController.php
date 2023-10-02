<?php

namespace App\Controller;

use App\Entity\Cart\CartItem;
use App\Repository\Module\ModuleRepository;
use App\Repository\Module\ScheduleRepository;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleFullCalendarEventsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository                 $moduleRepository,
        private readonly ScheduleRepository               $scheduleRepository,
        private readonly ModuleFullCalendarEventsProvider $moduleFullCalendarEventsProvider,
        private readonly CartProvider                     $cartProvider
    )
    {
    }

    #[Route('/reservation', name: 'APP_RESERVATION_SCHEDULES')]
    public function reservationShedules(): Response
    {
        return $this->render('reservation/reservation-schedules.html.twig', [
            'schedules' => $this->scheduleRepository->findAll(),
        ]);
    }

    #[Route('/reservation/{scheduleId}', name: 'APP_RESERVATION')]
    public function reservation(int $scheduleId): Response
    {
        if (!$schedule = $this->scheduleRepository->find($scheduleId)) {
            return $this->redirectToRoute('APP_RESERVATION_SCHEDULES');
        }

        $modules = $this->moduleRepository->findAll();

        $cart = $this->cartProvider->getUserCartOrCreate();

        return $this->render('reservation/reservation.html.twig', [
            'schedule' => $schedule,
            'modules' => $modules,
            'cart' => $cart,
            'events' => $this->moduleFullCalendarEventsProvider->getFullcalendarEventsDates($schedule, $modules, array_map(static function (CartItem $cartItem) {
                return $cartItem->getOccurenceId();
            }, $cart->getCartItems()->toArray())),
        ]);
    }
}
