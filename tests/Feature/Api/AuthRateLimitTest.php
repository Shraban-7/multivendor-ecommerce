<?php

use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('app.env', 'testing');
});

it('returns 429 after 6 verify-otp requests in 1 minute', function () {
    for ($i = 0; $i < 5; $i++) {
        $response = $this->postJson('/api/auth/verify-otp', [
            'phone' => '01700000000',
            'otp' => '123456',
        ]);
        expect($response->status())->not->toBe(429);
    }

    $response = $this->postJson('/api/auth/verify-otp', [
        'phone' => '01700000000',
        'otp' => '123456',
    ]);
    expect($response->status())->toBe(429);
});

it('returns 429 after 6 check-phone requests in 1 minute', function () {
    for ($i = 0; $i < 5; $i++) {
        $response = $this->postJson('/api/auth/check-phone', [
            'phone' => '017' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
        ]);
        expect($response->status())->not->toBe(429);
    }

    $response = $this->postJson('/api/auth/check-phone', [
        'phone' => '01799999999',
    ]);
    expect($response->status())->toBe(429);
});

it('returns 429 after 11 login requests in 1 minute', function () {
    for ($i = 0; $i < 10; $i++) {
        $response = $this->postJson('/api/auth/login', [
            'phone' => '017' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
            'password' => 'password',
        ]);
        expect($response->status())->not->toBe(429);
    }

    $response = $this->postJson('/api/auth/login', [
        'phone' => '01799999999',
        'password' => 'password',
    ]);
    expect($response->status())->toBe(429);
});

it('returns 429 after 11 register requests in 1 minute', function () {
    for ($i = 0; $i < 10; $i++) {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User ' . $i,
            'phone' => '017' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        expect($response->status())->not->toBe(429);
    }

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'phone' => '01799999999',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    expect($response->status())->toBe(429);
});
