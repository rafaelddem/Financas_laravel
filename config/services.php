<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'settings' => [
        'system_id' => env('SYSTEM_ID'),
        'my_owner_id' => env('MY_OWNER_ID'),
        'invoice_payment_category' => env('INVOICE_PAYMENT_CATEGORY'),
        'invoice_payment_method' => env('INVOICE_PAYMENT_METHOD'),
        'invoice_partial_payment_category' => env('INVOICE_PARTIAL_PAYMENT_CATEGORY'),
        'invoice_transaction_base' => env('INVOICE_TRANSACTION_BASE'),
        'transaction_limit_days_to_update' => env('TRANSACTION_LIMIT_DAYS_TO_UPDATE'),
        'transaction_limit_days_to_remove' => env('TRANSACTION_LIMIT_DAYS_TO_REMOVE'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
