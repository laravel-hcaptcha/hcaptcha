<?php


namespace LaravelHCaptcha\HCaptcha;

use Arcanedev\Html\Elements\{Div};
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use LaravelHCaptcha\HCaptcha\Exceptions\InvalidArgumentException;


class HCaptcha extends AbstractCaptcha
{
    // Decides if we've already loaded the script file or not.
    protected bool $scriptLoaded = false;


    private function getScriptSrc($callbackName = null): string
    {
        $queries = [];

        if ($this->hasLang()) Arr::set($queries, 'hl', $this->lang);

        if ($this->hasCallbackName($callbackName)) {
            Arr::set($queries, 'onload', $callbackName);
            Arr::set($queries, 'render', 'explicit');
        }

        return $this->getClientUrl() . (count($queries) ? '?' . http_build_query($queries) : '');
    }

    public function display($name = null, array $attributes = [])
    {
        return Div::make()->attributes(array_merge(static::prepareNameAttribute($name),
            $this->prepareAttributes($attributes)));
    }


    public function script(?string $callbackName = null): HtmlString
    {
        $script = '';

        if (!$this->scriptLoaded) {
            $script = '<script src="' . $this->getScriptSrc($callbackName) . '" async defer></script>';
            $this->scriptLoaded = true;
        }

        return self::toHtmlString($script);
    }

    public function getApiScript(): HtmlString
    {
        return self::toHtmlString("<script>
                window.lhcaptcha = {
                    captchas: [],
                    reset: function(name) {
                        let captcha = window.lhcaptcha.get(name);

                        if (captcha)
                            window.lhcaptcha.resetById(captcha.id);
                    },
                    resetById: function(id) {
                        hcaptcha.reset(id);
                    },
                    get: function(name) {
                        return window.lhcaptcha.find(function (captcha) {
                            return captcha.name === name;
                        });
                    },
                    getById: function(id) {
                        return window.lhcaptcha.find(function (captcha) {
                            return captcha.id === id;
                        });
                    },
                    find: function(callback) {
                        return window.lhcaptcha.captchas.find(callback);
                    },
                    render: function(name, sitekey) {
                        let captcha = {
                            id: hcaptcha.render(name, {'sitekey' : sitekey}),
                            name: name
                        };

                        window.lhcaptcha.captchas.push(captcha);

                        return captcha;
                    }
                }
            </script>");
    }

    /**
     * @param array $captchas
     * @param string $callbackName
     * @return HtmlString
     */
    public function scriptWithCallback(array $captchas, $callbackName = 'captchaRenderCallback')
    {
        $script = $this->script($callbackName)->toHtml();

        if (!empty($script) && !empty($captchas)) {
            $script = implode(PHP_EOL, [
                $this->getApiScript()->toHtml(),
                '<script>',
                "let $callbackName = function() {",
                $this->renderCaptchas($captchas),
                '};',
                '</script>',
                $script,
            ]);
        }

        return self::toHtmlString($script);
    }

    private function renderCaptchas(array $captchas): string
    {
        return implode(PHP_EOL, array_map(function ($captcha) {
            return "if (document.getElementById('{$captcha}')) { window.lhcaptcha.render('{$captcha}', '{$this->siteKey}'); }";
        }, $captchas));
    }

    private function hasCallbackName(?string $callbackName): bool
    {
        return !(is_null($callbackName) || trim($callbackName) === '');
    }


    protected function parseResponse($json)
    {
        return Response::fromJson($json);
    }

    private function prepareAttributes(array $attributes): array
    {
        $attributes = array_merge([
            'class'        => 'hcaptcha',
            'data-sitekey' => $this->siteKey,
        ], array_filter($attributes));

        self::checkDataAttribute($attributes, 'data-theme', [
            'light',
            'dark',
        ], 'light');

        self::checkDataAttribute($attributes, 'data-size', [
            'normal',
            'compact',
            'invisible',
        ], 'normal');

        return $attributes;
    }


    private static function checkDataAttribute(array &$attributes, $name, array $supported, $default): void
    {
        $attribute = $attributes[$name] ?? null;

        if (!is_null($attribute)) {
            $attribute = (is_string($attribute) && in_array($attribute,
                    $supported)) ? strtolower(trim($attribute)) : $default;

            $attributes[$name] = $attribute;
        }
    }

    /**
     * @param $name
     * @return array|false
     * @throws InvalidArgumentException
     */
    protected static function prepareNameAttribute($name)
    {
        if (is_null($name)) return [];

        if ($name === AbstractCaptcha::CAPTCHA_POST_PARAM) {
            throw new InvalidArgumentException('The captcha name must be different from "' . AbstractCaptcha::CAPTCHA_POST_PARAM . '".');
        }

        return array_combine([
            'id',
            'name',
        ], [
            $name,
            $name,
        ]);
    }
}
