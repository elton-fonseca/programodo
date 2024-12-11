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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /**
     * Environment variables used to conect to Payshop Gateway
     */
    'payshop' => [
        'environment' => env('POP_GATEWAY_ENVIRONMENT', 'sandbox'),

        'payment_services' => [
            'card' => env('POP_CARD_SERVICE_NAME', 'card'),
            'payshop' => env('POP_PAYSHOP_SERVICE_NAME', 'payshop'),
            'multibanco' => env('POP_MULTIBANCO_SERVICE_NAME', 'SIBS'),
            'mbway' => env('POP_MBWAY_SERVICE_NAME', 'SIBS'),
            'googlepay' => env('POP_GOOGLEPAY_SERVICE_NAME', 'googlepay'),
            'applepay' => env('POP_APPLEPAY_SERVICE_NAME', 'applepay'),
            'clicktopay' => env('POP_CLICKTOPAY_SERVICE_NAME', 'clicktopay'),
            'paypal' => env('POP_PAYPAL_SERVICE_NAME', 'paypal'),
        ],

        'payment_visible_names' => [
            'card' => env('VISIBLE_CARD_NAME', 'Cartão de Crédito'),
            'payshop' => env('VISIBLE_PAYSHOP_NAME', 'Payshop'),
            'multibanco' => env('VISIBLE_MULTIBANCO_NAME', 'Multibanco'),
            'mbway' => env('VISIBLE_MBWAY_NAME', 'MB WAY'),
            'googlepay' => env('VISIBLE_GOOGLEPAY_NAME', 'Google Pay'),
            'applepay' => env('VISIBLE_APPLEPAY_NAME', 'Apple Pay'),
            'clicktopay' => env('VISIBLE_CLICKTOPAY_NAME', 'Click to Pay'),
            'paypal' => env('VISIBLE_PAYPAL_NAME', 'Paypal'),
        ],

        'google_pay' => [
            'merchant_id' => env('GOOGLEPAY_MARCHANT_ID'),
            'merchant_name' => env('GOOGLEPAY_MARCHANT_NAME', 'Payshop'),
        ],

        'apple_pay' => [
            'merchant_name' => env('APPLEPAY_MARCHANT_NAME', 'Payshop'),
            'merchant_identifier' => env('APPLEPAY_MARCHANT_IDENTIFIER'),
            'domain_name' => env('APPLEPAY_DOMAIN_NAME'),
            'key' => env('APPLEPAY_KEY'),
            'certificate' => env('APPLEPAY_CERTIFICATE'),
        ],
    ],

    /**
     * Add credentials for Pay By Link API
     */
    'paybylink' => [
        'url_base' => env('PBL_API_URL'),
        'client_id' => env('PBL_CLIENT_ID'),
        'client_secret' => env('PBL_CLIENT_SECRET'),
        'switch_events_url' => env('PBL_SWITCH_EVENTS_URL'),

        'payment_methods' => [
            'card' => env('PBL_CARD_METHOD_NAME', 'card'),
            'payshop' => env('PBL_PAYSHOP_METHOD_NAME', 'payshop'),
            'multibanco' => env('PBL_MULTIBANCO_METHOD_NAME', 'multibanco'),
            'mbway' => env('PBL_MBWAY_METHOD_NAME', 'mbway'),
            'googlepay' => env('PBL_GOOGLEPAY_METHOD_NAME', 'googlepay'),
            'applepay' => env('PBL_APPLEPAY_METHOD_NAME', 'applepay'),
            'clicktopay' => env('PBL_CLICKTOPAY_METHOD_NAME', 'clicktopay'),
            'paypal' => env('PBL_PAYPAL_METHOD_NAME', 'paypal'),
        ],
    ],

];
