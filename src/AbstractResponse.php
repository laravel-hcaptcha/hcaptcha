<?php


namespace LaravelHCaptcha\HCaptcha;


abstract class AbstractResponse
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    // Your secret key is missing.
    public const E_MISSING_INPUT_SECRET = "missing-input-secret";

    // Your secret key is invalid or malformed.
    public const E_INVALID_INPUT_SECRET = 'invalid-input-secret';

    // The response parameter (verification token) is missing.
    public const E_MISSING_INPUT_RESPONSE = "missing-input-response";

    // The response parameter (verification token) is invalid or malformed
    public const E_INVALID_INPUT_RESPONSE = "invalid-input-response";

    // The request is invalid or malformed.
    public const E_BAD__REQUEST = "bad-request";

    // The error couldn't be found
    public const E_UNKNOWN_ERROR = "unknown-request";

    // The json is invalid
    public const E_INVALID_JSON = "invalid-json";

    /* -----------------------------------------------------------------
    |  Properties
    | -----------------------------------------------------------------
    */

    // Success or failure.
    protected bool $success = false;

    // Error code strings.
    protected array $errorCodes = [];

    //  The hostname of the site where the hCaptcha was solved. Only use for statistics.
    protected ?string $hostname;

    //  Timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
    protected ?string $challengeTs;


    public function __construct(
        $success, array $errorCodes = [], $hostname = null, $challengeTs = null, $score = null, $action = null
    ) {
        $this->success = $success;
        $this->errorCodes = $errorCodes;
        $this->hostname = $hostname;
        $this->challengeTs = $challengeTs;
    }

    /* -----------------------------------------------------------------
    |  Getters
    | -----------------------------------------------------------------
    */
    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    // Get challenge timestamp
    public function getChallengeTs()
    {
        return $this->challengeTs;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    // Build the response from the expected JSON returned by the service.
    public static function fromJson(string $json): self
    {
        $responseData = json_decode($json, true);

        if (!$responseData) return new static(false, [self::E_INVALID_JSON]);

        return static::fromArray($responseData);
    }

    // Build the response from an array.
    abstract public static function fromArray(array $array);

    // Convert the response object to array.
    abstract public function toArray(): array;


    // Convert the object to its JSON representation.
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    // Convert the response object to array.
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    // Check if the response is successful.
    public function isSuccess(): bool
    {
        return $this->success === true;
    }

    // @warn Don't use this to make strict decision
    public function isHostname(string $hostname): bool
    {
        return $this->getHostname() === $hostname;
    }

}
