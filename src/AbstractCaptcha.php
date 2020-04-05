<?php


namespace LaravelHCaptcha\HCaptcha;


use Illuminate\Support\HtmlString;
use LaravelHCaptcha\HCaptcha\Contracts\HCapchaContract;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractCaptcha implements HCapchaContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */
    const CAPTCHA_POST_PARAM = 'h-recaptcha-response';

    // Shared key that backend use for showing verifying with hcaptcha
    protected string $secret;

    // Key used in frontend to show captcha from hcaptcha
    protected string $siteKey;

    // Force HCaptcha to use the language in frontend
    protected string $lang;


    // HTTP Request client
    protected Request $request;


    // Recaptcha response
    protected ?Response $response;


    public function __construct(string $secret, string $siteKey, ?string $lang = null)
    {
        $this->setSecret($secret);
        $this->setSiteKey($siteKey);
        $this->setLang($lang);

        $this->setRequestClient(new Request);
    }


    /**
     * @param string $secret
     * @return $this
     * @throws Exceptions\ApiException
     */
    protected function setSecret(string $secret): self
    {
        self::checkKey('secret key', $secret);

        $this->secret = $secret;

        return $this;
    }

    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * @param string $siteKey
     * @return $this
     * @throws Exceptions\ApiException
     */
    protected function setSiteKey(string $siteKey): self
    {
        self::checkKey('site key', $siteKey);

        $this->siteKey = $siteKey;

        return $this;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }


    public function setRequestClient(Request $request): self
    {
        $this->request = $request;

        return $this;
    }


    public function getLastResponse(): ?AbstractResponse
    {
        return $this->response;
    }


    public static function getClientUrl(): string
    {
        return "https://hcaptcha.com/1/api.js";
    }

    public static function getVerificationUrl(): string
    {
        return "https://hcaptcha.com/siteverify";
    }

    /**
     * @param $response
     * @param null $clientIp
     * @return AbstractResponse|null
     * @throws Exceptions\InvalidUrlException
     */
    public function verify($response, $clientIp = null): ?AbstractResponse
    {
        return $this->response = $this->sendVerifyRequest([
            'secret'   => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp,
        ]);
    }

    public function verifyRequest(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        $server = $request->getServerParams();

        return $this->verify($body[self::CAPTCHA_POST_PARAM] ?? '', $server['REMOTE_ADDR'] ?? null);
    }

    /**
     * @param array $query
     * @return mixed
     * @throws Exceptions\InvalidUrlException
     */
    protected function sendVerifyRequest(array $query = [])
    {
        $query = array_filter($query);
        $json = $this->request->send($this->getVerificationUrl() . '?' . http_build_query($query));

        return $this->parseResponse($json);
    }

    abstract protected function parseResponse(string $json);

    protected function hasLang(): bool
    {
        return !empty($this->lang);
    }

    /**
     * @param $name
     * @param $value
     * @throws Exceptions\ApiException
     */
    private static function checkKey($name, &$value): void
    {
        self::checkIsString($name, $value);

        $value = trim($value);

        self::checkIsNotEmpty($name, $value);
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Exceptions\ApiException
     */
    private static function checkIsString(string $name, string $value): void
    {
        if (!is_string($value)) {
            throw new Exceptions\ApiException("The {$name} must be a string value, " . gettype($value) . ' given.');
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Exceptions\ApiException
     */
    private static function checkIsNotEmpty(string $name, string $value): void
    {
        if (empty($value)) {
            throw new Exceptions\ApiException("The {$name} must not be empty");
        }
    }

    protected static function toHtmlString($html): HtmlString
    {
        return new HtmlString($html);
    }
}
