<?php

namespace App\Twig\Runtime;

use Carbon\Carbon;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function carbon_format(\DateTimeInterface $dateTime, string $format): string
    {
        $dt = Carbon::createFromFormat(DATE_ATOM, $dateTime->format(DATE_ATOM));
        $dt->locale('fr_FR');
        return $dt->translatedFormat($format);
    }
}
