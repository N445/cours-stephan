<?php

namespace App\Service\Cart\EmailPdf;

use App\Entity\Cart\Cart;
use Knp\Snappy\Pdf;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;


class CartToPdf
{
    public function __construct(
        private readonly Environment         $twig,
        private readonly Security            $security,
        private readonly KernelInterface     $kernel
    )
    {
    }

    public function getPdf(Cart $cart): string
    {
        $user = $this->security->getUser();

        $html = $this->twig->render('email-pdf/pdf/payment.html.twig', [
            'cart'              => $cart,
            'user'               => $user,
        ]);

//        return $html;

        $tmpFile = (new Filesystem())->tempnam(sys_get_temp_dir(), 'novenci-product-order-confirmation');


        (new Pdf())
            ->setBinary($this->kernel->getProjectDir() . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')
            ->setOption('encoding', 'UTF-8')
            ->setOption('enable-local-file-access', true)
            ->setOption('margin-top', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10)
//            ->setOption('margin-bottom', 10)
            ->setOption('margin-bottom', 35)
            ->setOption('enable-javascript', true)
            ->setOption('footer-html', $this->twig->render('email-pdf/includes/_footer.html.twig'))
//            ->setOption('replace', [
//                'current_page' => '[page]',      // Replace '[current_page]' by page number
//            ])
            ->generateFromHtml($html, $tmpFile, [], true)
        ;

        return $tmpFile;
    }
}
