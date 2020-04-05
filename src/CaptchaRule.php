<?php


namespace LaravelHCaptcha\HCaptcha;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class CaptchaRule implements Rule
{

    protected $skipIps = [];

    public function __construct()
    {
        $this->skipIps(config()->get('no-captcha.skip-ips', []));
    }


    /**
     * Set the ips to skip.
     *
     * @param string|array $ip
     *
     * @return $this
     */
    public function skipIps($ip)
    {
        $this->skipIps = Arr::wrap($ip);

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main methods
     | -----------------------------------------------------------------
     */

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ip = request()->ip();

        if (in_array($ip, $this->skipIps)) return true;

        return h_captcha($this->version)->verify($value, $ip)->isSuccess();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return (string)trans('validation.captcha');
    }
}
