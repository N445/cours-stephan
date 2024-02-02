<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EmailPdfExtension extends AbstractExtension
{
    private string $publicDir;

    public function __construct(
        private readonly EntrypointLookupInterface $entrypointLookup,
        private readonly KernelInterface $kernel
    )
    {
        $this->publicDir        = $kernel->getProjectDir() . '/public';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('encore_entry_css_source', [$this, 'getEncoreEntryCssSource']),
            new TwigFunction('imageUrlToBase64', [$this, 'imageUrlToBase64']),
        ];
    }

    /**
     * @param string $entryName
     * @return string
     */
    public function getEncoreEntryCssSource(string $entryName): string
    {
        $files = $this->entrypointLookup
            ->getCssFiles($entryName)
        ;

        $this->entrypointLookup->reset();

        $source = '';

        foreach ($files as $file) {
            $source .= file_get_contents($this->publicDir . '/' . $file);
        }

        return $source;
    }

    public function imageUrlToBase64(string $imageUrl): string
    {
        return 'data:image/jpg;base64,'.base64_encode(file_get_contents($imageUrl));
    }

    /**
     * @return string[]
     */
    public static function getSubscribedServices(): array
    {
        return [
            EntrypointLookupInterface::class,
        ];
    }
}
