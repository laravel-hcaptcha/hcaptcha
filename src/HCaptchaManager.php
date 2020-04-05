<?php


namespace LaravelHCaptcha\HCaptcha;

use Illuminate\Support\Manager;
use LaravelHCaptcha\HCaptcha\Contracts\HCapchaContract;
use LaravelHCaptcha\HCaptcha\Contracts\HCaptchaManagerContract;

class HCaptchaManager extends Manager implements HCaptchaManagerContract
{
    public function getDefaultDriver(): string
    {
        return $this->config('driver');
    }

    public function setDriver(?string $driver_name = null): HCapchaContract
    {
        return $this->driver($driver_name);
    }

    /**
     * Create the v1 captcha.
     *
     * @return HCaptcha
     */
    public function createV1Driver(): HCapchaContract
    {
        return $this->buildDriver(HCaptcha::class);
    }

    protected function buildDriver(string $driver): HCapchaContract
    {
        return $this->container->make($driver, [
            'secret'  => $this->config('secret'),
            'siteKey' => $this->config('sitekey'),
            'lang'  => $this->config('lang') ?: $this->container->getLocale(),
        ]);
    }


    /**
     * Get a value from the config file.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function config(string $key = '')
    {
        return $this->config->get("hcaptcha.{$key}");
    }

}
