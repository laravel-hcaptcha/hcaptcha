<?php

use LaravelHCaptcha\HCaptcha\Contracts\HCapchaContract;
use LaravelHCaptcha\HCaptcha\Contracts\HCaptchaManagerContract;
use LaravelHCaptcha\HCaptcha\HCaptcha;
use LaravelHCaptcha\HCaptcha\HCaptchaInvisibleV1;

if (!function_exists('h_captcha')) {
    /**
     * Get the hCaptcha builder.
     * @param string|null $driver
     * @return HCaptcha|HCaptchaInvisibleV1
     */
    function h_captcha(?string $driver = null)
    {
        return is_null($driver) ? app(HCapchaContract::class) : app(HCaptchaManagerContract::class)->driver($driver);
    }
}
