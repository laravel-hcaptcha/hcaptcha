<?php

declare(strict_types=1);

namespace LaravelHCaptcha\HCaptcha;


use LaravelHCaptcha\HCaptcha\Contracts\RequestContract;
use LaravelHCaptcha\HCaptcha\Exceptions\InvalidUrlException;

class Request implements RequestContract
{
    /* -----------------------------------------------------------------
    |  Properties
    | -----------------------------------------------------------------
    */

    /**
     * URL to request.
     *
     * @var string
     */
    protected string $url;

    /* -----------------------------------------------------------------
   |  Getters & Setters
   | -----------------------------------------------------------------
   */

    /**
     * Set URL.
     *
     * @param string $url
     *
     * @return self
     * @throws InvalidUrlException
     */
    protected function setUrl($url): self
    {
        $this->checkUrl($url);

        $this->url = $url;

        return $this;
    }

    /* -----------------------------------------------------------------
    |  Main Methods
    | -----------------------------------------------------------------
    */

    /**
     * Create an api request using curl.
     *
     * @return string
     */
    protected function curl()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Run the request and get response.
     *
     * @param string $url
     * @param bool $curled
     *
     * @return string
     * @throws InvalidUrlException
     */
    public function send(string $url, bool $curled = true): string
    {
        $this->setUrl($url);

        $result = ($this->isCurlExists() && $curled === true) ? $this->curl() : file_get_contents($this->url);

        return $this->checkResult($result) ? $result : '{}';
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check URL.
     *
     * @param string $url
     *
     * @throws InvalidUrlException
     */
    private function checkUrl(&$url): void
    {
        if (!is_string($url)) throw new InvalidUrlException('The url must be a string value, ' . gettype($url) . ' given');

        $url = trim($url);

        if (empty($url)) throw new InvalidUrlException('The url must not be empty');

        if (filter_var($url,
                FILTER_VALIDATE_URL) === false) throw new InvalidUrlException('The url [' . $url . '] is invalid');
    }

    /**
     * Check if curl exists.
     *
     * @return bool
     */
    private function isCurlExists(): bool
    {
        return function_exists('curl_version');
    }


    /**
     * Check Result.
     *
     * @param string | null $result
     *
     * @return bool
     */
    private function checkResult($result): bool
    {
        return is_string($result) && !empty($result);
    }

}
