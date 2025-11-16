<?php

namespace App\Enums;

enum SubscriptionAction: string
{
    case CREATED = 'created';
    case UPGRADED = 'upgraded';
    case DOWNGRADED = 'downgraded';
    case RENEWED = 'renewed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
}
