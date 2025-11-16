<?php

namespace App\Enums;

enum SubscriptionPaymentMethod: string
{
    case CARD = 'card';
    case BANK = 'bank';
    case WALLET = 'wallet';
    case MANUAL = 'manual';
}
