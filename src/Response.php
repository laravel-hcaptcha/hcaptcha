<?php


namespace LaravelHCaptcha\HCaptcha;


class Response extends AbstractResponse
{
    public static function fromArray(array $array)
    {
        $hostname = $array['hostname'] ?? null;
        $challengeTs = $array['challenge_ts'] ?? null;

        if (isset($array['success']) && $array['success'] == true) {
            return new static(true, [], $hostname, $challengeTs);
        }

        if (!(isset($array['error-codes']) && is_array($array['error-codes']))) {
            $array['error-codes'] = [AbstractResponse::E_UNKNOWN_ERROR];
        }

        return new static(false, $array['error-codes'], $hostname, $challengeTs);
    }

    // Convert the response object to array.
    public function toArray(): array
    {
        return [
            'success'      => $this->isSuccess(),
            'hostname'     => $this->getHostname(),
            'challenge_ts' => $this->getChallengeTs(),
            'error-codes'  => $this->getErrorCodes(),
        ];
    }
}
