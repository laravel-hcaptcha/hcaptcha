<?php

declare(strict_types=1);

namespace LaravelHCaptcha\HCaptcha\Contracts;


interface RequestContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the request and get response.
     *
     * @param string $url
     * @param bool $curled
     *
     * @return string
     */
    public function send(string $url, bool $curled = true): string;
}
