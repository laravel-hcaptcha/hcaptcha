<?php


namespace LaravelHCaptcha\HCaptcha\Contracts;


use LaravelHCaptcha\HCaptcha\Exceptions\ResponseV1InvisibleCaptcha;
use LaravelHCaptcha\HCaptcha\HCaptcha;

interface HCaptchaManagerContract
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the driver to use
     *
     * @param string|null $driver
     * @return HCaptcha| ResponseV1InvisibleCaptcha
     */
    public function setDriver(?string $driver = null);
}
