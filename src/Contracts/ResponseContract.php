<?php


namespace LaravelHCaptcha\HCaptcha\Contracts;


use LaravelHCaptcha\HCaptcha\AbstractResponse;

interface ResponseContract
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */
    /**
     * Build the response from the expected JSON returned by the service.
     *
     * @param string $json
     *
     * @return  AbstractResponse
     */
    public static function fromJson(string $json): AbstractResponse;

    /**
     * Build the response from an array.
     *
     * @param array $array
     *
     * @return AbstractResponse
     */
    public static function fromArray(array $array): AbstractResponse;

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrorCodes(): array;

    /**
     * Get hostname.
     *
     * @return string
     */
    public function getHostname(): string;

    /**
     * Get challenge timestamp
     *
     * @return string
     */
    public function getChallengeTs(): string;


    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get score
     *
     * @return float
     */
    public function getScore(): float;

    /**
     * Get action
     *
     * @return string
     */
    public function getAction(): string;

    /* -----------------------------------------------------------------
    |  Check Methods
    | -----------------------------------------------------------------
    */

    /**
     * Check if the response is successful.
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Check the score.
     *
     * @param float $score
     *
     * @return bool
     */
    public function isScore(float $score): bool;


    /**
     * Check the hostname.
     *
     * @param string $hostname
     *
     * @return bool
     */
    public function isHostname(string $hostname): bool;

    /**
     * Check the action name.
     *
     * @param string $action
     *
     * @return bool
     */
    public function isAction(string $action): bool;
}
