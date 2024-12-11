<?php

namespace App\Enums;

enum PaymentType: string
{
    case CARD = 'card';
    case PAYSHOP = 'payshop';
    case MULTIBANCO = 'multibanco';
    case MBWAY = 'mbway';
    case GOOGLEPAY = 'googlepay';
    case APPLEPAY = 'applepay';
    case CLICKTOPAY = 'clicktopay';
    case PAYPAL = 'paypal';
}
