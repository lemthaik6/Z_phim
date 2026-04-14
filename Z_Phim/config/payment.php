<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */

    'default' => env('PAYMENT_GATEWAY', 'simulation'),

    /*
    |--------------------------------------------------------------------------
    | Simulation Gateway (for testing without real payment)
    |--------------------------------------------------------------------------
    */
    'simulation' => [
        'driver' => 'simulation',
    ],

    /*
    |--------------------------------------------------------------------------
    | VNPay Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'vnpay' => [
        'driver' => 'vnpay',
        'tmn_code' => env('VNPAY_TMN_CODE', ''),
        'hash_secret' => env('VNPAY_HASH_SECRET', ''),
        'return_url' => env('VNPAY_RETURN_URL', 'http://localhost:8000/api/payments/vnpay/callback'),
        'is_sandbox' => env('VNPAY_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | MoMo Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'momo' => [
        'driver' => 'momo',
        'partner_code' => env('MOMO_PARTNER_CODE', ''),
        'access_key' => env('MOMO_ACCESS_KEY', ''),
        'secret_key' => env('MOMO_SECRET_KEY', ''),
        'return_url' => env('MOMO_RETURN_URL', 'http://localhost:8000/api/payments/momo/callback'),
        'is_sandbox' => env('MOMO_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Status and Payment Flow
    |--------------------------------------------------------------------------
    | 
    | BOOKING STATUS:
    | - pending: Booking created, seats locked for 5 minutes, awaiting payment
    | - paid: Payment completed, booking confirmed
    | - cancelled: User cancelled or lock expired
    |
    | SEAT LOCK:
    | - Seats are locked for 5 minutes when booking is created
    | - After 5 minutes, if not paid, seats are released and booking cancelled
    | - Job ReleaseExpiredSeatLocks runs periodically to clean up
    |
    | PAYMENT STATUS:
    | - pending: Payment initialized
    | - completed: Payment successful
    | - failed: Payment failed
    | - refunded: Refund processed
    */
];