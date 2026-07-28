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
    'performance' => [
        // Service Level Agreement for shipping (in hours) — orders shipped within this are 'on time'
        'shipping_sla_hours' => (int) env('MARKETPLACE_SHIPPING_SLA_HOURS', 48),
        // Service Level Agreement for chat first-response (in hours)
        'chat_response_sla_hours' => (int) env('MARKETPLACE_CHAT_RESPONSE_SLA_HOURS', 24),
        // Scoring weights — must sum to 1.0
        'weights' => [
            'cancellation' => (float) env('MARKETPLACE_PERF_WEIGHT_CANCELLATION', 0.30),
            'late_shipping' => (float) env('MARKETPLACE_PERF_WEIGHT_LATE_SHIPPING', 0.25),
            'rating' => (float) env('MARKETPLACE_PERF_WEIGHT_RATING', 0.25),
            'response' => (float) env('MARKETPLACE_PERF_WEIGHT_RESPONSE', 0.10),
            'dispute' => (float) env('MARKETPLACE_PERF_WEIGHT_DISPUTE', 0.10),
        ],
        // Cancellation/late/dispute rates above these thresholds cap the subscore at 0
        'thresholds' => [
            'cancellation_max' => (float) env('MARKETPLACE_CANCEL_MAX', 0.20),
            'late_shipping_max' => (float) env('MARKETPLACE_LATE_MAX', 0.30),
            'dispute_max' => (float) env('MARKETPLACE_DISPUTE_MAX', 0.30),
        ],
        // Tier thresholds (overall score 0-100)
        'min_orders_for_scoring' => (int) env('MARKETPLACE_PERF_MIN_ORDERS', 5),
        'auto_recompute' => env('MARKETPLACE_PERF_AUTO_RECOMPUTE', true),
        // Daily snapshot retention (days)
        'snapshot_retention_days' => (int) env('MARKETPLACE_PERF_SNAPSHOT_DAYS', 180),
    ],
];
