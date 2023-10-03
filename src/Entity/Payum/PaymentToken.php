<?php

namespace App\Entity\Payum;


use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

#[ORM\Entity]
#[ORM\Table]
class PaymentToken extends Token
{
}