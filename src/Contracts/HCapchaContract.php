<?php


namespace LaravelHCaptcha\HCaptcha\Contracts;


use Illuminate\Support\HtmlString;
use LaravelHCaptcha\HCaptcha\AbstractResponse;
use LaravelHCaptcha\HCaptcha\Request;

interface HCapchaContract
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */
    // Set the http request client
    public function setRequestClient(Request $request): self;

    // Set the language of captcha
    public function setLang(string $lang): self;

    // Verify the response
    public function verify($response, $clientIp = null): ?AbstractResponse;

    // Get script tag.
    public function script(): HtmlString;

    // Get HCaptcha Api Script
    public function getApiScript(): HtmlString;
}
