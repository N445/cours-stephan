<?php

namespace App\Twig\Runtime;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly KernelInterface $kernel
    )
    {
    }

    public function carbon_format(\DateTimeInterface $dateTime, string $format): string
    {
        $dt = Carbon::createFromFormat(DATE_ATOM, $dateTime->format(DATE_ATOM));
        $dt->locale('fr_FR');
        return $dt->translatedFormat($format);
    }

    public function getPublicPath(): string
    {
        return $this->kernel->getProjectDir() . '/public';
    }
}
