<?php

namespace App\Service\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;

class ModuleOccurenceHelper
{
    public function __construct(
        private readonly ModuleEventsProvider $moduleEventsProvider,
    )
    {
    }

    public function getRemainingPlaces(Schedule $schedule, Module $module, string $occurence): int
    {
        return 0;
    }
}