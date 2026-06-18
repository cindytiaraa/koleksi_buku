<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    | Ambil dari: https://dashboard.sandbox.midtrans.com
    | Menu: Settings > Access Keys
    | Format Sandbox: SB-Mid-client-7LwzY9d-zgDFaC8i
    */
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    | Dipakai di frontend untuk load Snap.js
    | Format Sandbox: SB-Mid-client-7LwzY9d-zgDFaC8i
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Mode Production
    |--------------------------------------------------------------------------
    | false = Sandbox (testing, gratis, tidak butuh dokumen)
    | true  = Production (butuh dokumen & verifikasi Midtrans)
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
];
