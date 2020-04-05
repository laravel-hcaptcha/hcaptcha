<?php

return [

    /* -----------------------------------------------------------------
     |  API Keys
     | -----------------------------------------------------------------
     */
    'secret'  => env('HCAPTCHA_SECRET', 'h-captcha-secret'),
    'sitekey' => env('HCAPTCHA_SITEKEY', 'h-captcha-sitekey'),

    /* -----------------------------------------------------------------
     |  Drivers
     | -----------------------------------------------------------------
     |  Supported: v1  (more drivers will be added as required)
     */

    'driver'   => 'v1',

    /* -----------------------------------------------------------------
     |  Localization
     | -----------------------------------------------------------------
     | Check https://docs.hcaptcha.com/languages for available language
     */
    'lang'     => null,

    /* -----------------------------------------------------------------
     |  Skip IPs
     | -----------------------------------------------------------------
     */
    'skip-ips' => [
        // Add IP Here that will be skipped from captcha checks. Eg.
        // 127.0.0.1
    ],

];
