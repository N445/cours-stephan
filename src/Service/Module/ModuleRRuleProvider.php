<?php

namespace App\Service\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Planning;
use App\Entity\Module\Schedule;
use RRule\RRule;

class ModuleRRuleProvider
{
    public static function getDayRRule(string $day, \DateTimeImmutable $startAt, \DateTimeImmutable $endAt): RRule
    {
        return new RRule(
            [
                'FREQ' => RRule::WEEKLY,
                'BYDAY' => $day,
                'INTERVAL' => 1,
//                'BYSETPOS' => -1,
                'DTSTART' => $startAt->format('Y-m-d'),
                'UNTIL' => $endAt->format('Y-m-d'),
            ],
        );
    }
}