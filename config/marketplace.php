<?php

return [
    'return_window_days' => env('MARKETPLACE_RETURN_WINDOW_DAYS', 7),
    'return_auto_approve_hours' => env('MARKETPLACE_RETURN_AUTO_APPROVE_HOURS', 48),
    'allow_partial_returns' => true,
    'allow_exchange' => true,
    'dispute_window_days' => env('MARKETPLACE_DISPUTE_WINDOW_DAYS', 14),
    'refund' => [
        'wallet_fallback' => true,
        'require_item_received' => true,
        'auto_credit_wallet_when_gateway_fails' => true,
        'wallet_payout_max_attempts' => 3,
    ],
];
