<?php

namespace App\Controller;

use App\Entity\Cart\CartItem;
use App\Entity\Module\Planning;
use App\Entity\Module\Schedule;
use App\Repository\Module\ModuleRepository;
use App\Repository\Module\ScheduleRepository;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleEventsProvider;
use App\Service\Module\ModuleFullCalendarEventsProvider;
use App\Service\Module\Schedule\ScheduleFullCalendarEventsProvider;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository                   $moduleRepository,
        private readonly ScheduleRepository                 $scheduleRepository,
        private readonly ModuleFullCalendarEventsProvider   $moduleFullCalendarEventsProvider,
        private readonly CartProvider                       $cartProvider,
        private readonly ScheduleFullCalendarEventsProvider $scheduleFullCalendarEventsProvider,
        private readonly ModuleEventsProvider $moduleEventsProvider,
    )
    {
    }

    #[Route('/reservation', name: 'APP_RESERVATION_SCHEDULES')]
    public function reservationShedules(): Response
    {
        $schedules = $this->scheduleRepository->getAvailableSchedules();
        $this->scheduleFullCalendarEventsProvider->getFullcalendarEventsDates($schedules);
        return $this->render('reservation/reservation-schedules.html.twig', [
            'schedules'             => $schedules,
            'schedulesFulCallendar' => $this->scheduleFullCalendarEventsProvider->getFullcalendarEventsDates($schedules),
        ]);
    }

    #[Route('/reservation/module/{moduleId}', name: 'APP_RESERVATION_SCHEDULES_BY_MODULE')]
    public function reservationShedulesByModule(int $moduleId): Response
    {
        if (!$module = $this->moduleRepository->betById($moduleId)) {
            return $this->redirectToRoute('APP_RESERVATION_SCHEDULES');
        }

        $schedules = array_map(function (Planning $planning): Schedule {
            return $planning->getSchedule();
        }, $module->getPlannings()->toArray());

        return $this->render('reservation/reservation-schedules.html.twig', [
            'schedules'             => $schedules,
            'schedulesFulCallendar' => $this->scheduleFullCalendarEventsProvider->getFullcalendarEventsDates($schedules),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/reservation/{scheduleId}', name: 'APP_RESERVATION')]
    public function reservation(int $scheduleId): Response
    {
        if (!$schedule = $this->scheduleRepository->getAvailableSchedule($scheduleId)) {
            return $this->redirectToRoute('APP_RESERVATION_SCHEDULES');
        }

        $modules = $this->moduleRepository->getModulesBySchedule($schedule);

        $cart = $this->cartProvider->getUserCartOrCreate();

        $events = $this->moduleFullCalendarEventsProvider->getFullcalendarEventsDates(
            schedule     : $schedule,
            modules      : $modules,
            occurencesIDs: array_map(static function (CartItem $cartItem) {
                               return $cartItem->getOccurenceId();
                           }, $cart->getCartItems()->toArray()),
        );

        return $this->render('reservation/reservation.html.twig', [
            'schedule' => $schedule,
            'modules'  => $modules,
            'cart'     => $cart,
            'events'   => $events,
        ]);
    }
}
