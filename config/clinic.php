<?php

return [
    'name' => env('CLINIC_NAME', 'Clínica Médica'),
    'address' => env('CLINIC_ADDRESS', ''),
    'phone' => env('CLINIC_PHONE', ''),
    'email' => env('CLINIC_EMAIL', ''),
    'tax_rate' => env('CLINIC_TAX_RATE', 0.16),
    'currency' => env('CLINIC_CURRENCY', 'MXN'),

    'appointment' => [
        'default_duration' => env('APPOINTMENT_DEFAULT_DURATION', 30),
        'max_advance_days' => env('APPOINTMENT_MAX_ADVANCE_DAYS', 60),
        'cancellation_hours' => env('APPOINTMENT_CANCELLATION_HOURS', 24),
    ],

    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', false),
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', ''),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN', ''),
    ],

    'pdf' => [
        'orientation' => 'portrait',
        'paper_size' => 'letter',
    ],
];
