# hCaptcha (new captcha) [![Packagist License][badge_license]](LICENSE.md) [![For PHP][badge_php]][link-github-repo]

[![Github Workflow Status][badge_build]][link-github-status]
[![Coverage Status][badge_coverage]][link-scrutinizer]
[![Scrutinizer Code Quality][badge_quality]][link-scrutinizer]
[![SensioLabs Insight][badge_insight]][link-insight]
[![Github Issues][badge_issues]][link-github-issues]

[![Packagist][badge_package]][link-packagist]
[![Packagist Release][badge_release]][link-packagist]
[![Packagist Downloads][badge_downloads]][link-packagist]

*By [shirshak55](http://www.github.com/shirshak55)* with <

Please wait for v1 to use. Currently in development. If everything goes ok should be released before end of this month
## What is hCaptcha?

> hCaptcha is a is a CAPTCHA-like system designed to establish that a computer user is human. The team behind hCaptcha has decades of software and ML expertise and they build and operate massively scalable systems to tackle today's hardest problems. 
> - [hcaptcha](https://hcaptcha.com)

![New hCaptcha](https://assets.website-files.com/5c73e7ea3f8bb2a85d2781db/5d79dae4fedbb6664b84ddb3_challenge-bounding_box-0.jpg)

### Features

  * Framework agnostic package.
  * Well supported
  * Easy setup &amp; configuration.
  * Well documented &amp; IDE Friendly.
  * Well tested with maximum code quality.
  * Laravel  `7.x`  supported.
  * PSR-7 Support (ServerRequest verification).
  * Made with :heart: &amp; :coffee:.

### Support
Like Spatie I am only supporting above php 7.4 and Laravel Version above 7. 
## Steps

  1. Installation and Setup
  
  To use hcaptcha, you need to have a `site key` and a `secret key`. [Click here](https://dashboard.hcaptcha.com) to setup a domain and get your keys.

The `site key` is using for the widget and the `secret key` is used to validate the response we get from hcaptcha.

For more details, check the [official documentation](https://docs.hcaptcha.com/).
   
   You can install this package via [Composer](http://getcomposer.org/) by running this command `composer require laravel-hcaptcha/hcaptcha`.
   
   
   > **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    LaravelHcaptcha\Hcaptcha\HCaptchaServiceProvider::class,
],
```

   
  2. Configuration
  
 ````
// Edit your .env file by adding this two lines and fill it with your keys.

HCAPTCHA_SECRET=your-secret-key
HCAPTCHA_SITEKEY=your-site-key
````

In Laravel you can publish (But is not required usually)

Run `php artisan vendor:publish  --provider="LaravelHCaptcha\Hcaptcha\HcaptchaServiceProvider"` to publish the config file.

Edit the `secret` and `sitekey` values in `config/hcaptcha.php` file:
  3. Usage

Example in vanilla php


```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

use LaravelHcaptcha\HCaptcha\HCaptcha;

$secret  = 'your-secret-key';
$sitekey = 'your-site-key';
$captcha = new HCaptcha($secret, $sitekey);

if ($_POST) {
    // You need to check also if the $_POST['g-recaptcha-response'] is not empty.
    $response = $captcha->verify($_POST['h-recaptcha-response'] ?? null);

    echo $response->isSuccess()
        ? 'Yay ! You are a human.'
        : 'No ! You are a robot.';

    exit();
}

?>

<form action="?" method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Submit</button>
</form>

<?php
// At the bottom, before the </body> (If you're a good programmer and you listen to your mother)
echo $captcha->script();
?>
```


### Laravel

#### Views

Insert reCAPTCHA inside your form using one of this examples:

```php
<form ...>
    // Other inputs...
    {!! no_captcha()->display()->toHtml() !!}
    <input type
</form>

{{ no_captcha()->script()->toHtml(); }}
```

#### Back-end (Controller or somewhere in your project ...)

To validate the response we get from Google, your can use the `captcha` rule in your validator:

```php
use LaravelHCaptcha\HCaptcha\CaptchaRule;

$inputs   = request()->all();
$rules    = [
    // Other validation rules...
    'h-recaptcha-response' => ['required', new CaptchaRule],
];
$messages = [
    'h-recaptcha-response.required' => 'Your custom validation message.',
    'h-recaptcha-response.captcha'  => 'Your custom validation message.',
];

$validator = Validator::make($inputs, $rules, $messages);

if ($validator->fails()) {
    $errors = $validator->messages();

    var_dump($errors->first('g-recaptcha-response'));

    // Redirect back or throw an error
}
```

## Contribution

Any ideas are welcome. Feel free to submit any issues or pull requests, please check the [contribution guidelines](CONTRIBUTING.md).

## Security

If you discover any security related issues, please email shirshak55@gmail.com instead of using the issue tracker.

## Credits
- [Shirshak][link-author]
- [ARCANEDEV](https://github.com/arcanedev-maroc) (Thanks to his recaptcha plugin i was able to make this captcha)
- [All Contributors][link-contributors]

[badge_php]:          https://img.shields.io/badge/PHP-Framework%20agnostic-4F5B93.svg?style=flat-square
[badge_license]:      https://img.shields.io/packagist/l/laravel-hcaptcha/hcaptcha.svg?style=flat-square
[badge_build]:       https://img.shields.io/github/workflow/status/laravel-hcaptcha/hcaptcha/run-tests?style=flat-square
[badge_coverage]:     https://img.shields.io/scrutinizer/coverage/g/laravel-/hcaptcha.svg?style=flat-square
[badge_quality]:      https://img.shields.io/scrutinizer/g/laravel-hcaptcha/hcaptcha.svg?style=flat-square
[badge_insight]:      https://img.shields.io/sensiolabs/i/ae37b4c0-5478-4afb-9a71-1fe5534d8ef5.svg?style=flat-square
[badge_issues]:       https://img.shields.io/github/issues/laravel-hcaptcha/hcaptcha.svg?style=flat-square
[badge_package]:      https://img.shields.io/badge/package-laravel-captcha/no--captcha-blue.svg?style=flat-square
[badge_release]:      https://img.shields.io/packagist/v/laravel-hcaptcha/hcaptcha.svg?style=flat-square
[badge_downloads]:    https://img.shields.io/packagist/dt/laravel-hcaptcha/hcaptcha.svg?style=flat-square

[link-author]:        https://github.com/shirshak55
[link-github-repo]:   https://github.com/laravel-hcaptcha/hcaptcha
[link-github-status]: https://github.com/laravel-hcaptcha/hcaptcha/actions
[link-github-issues]: https://github.com/laravel-hcaptcha/hcaptcha/issues
[link-contributors]:  https://github.com/laravel-hcaptcha/hcaptcha/graphs/contributors
[link-packagist]:     https://packagist.org/packages/laravel-hcaptcha/hcaptcha
[link-scrutinizer]:   https://scrutinizer-ci.com/g/laravel-hcaptcha/noCAPTCHA/?branch=master
[link-insight]:       https://insight.sensiolabs.com/projects/ae37b4c0-5478-4afb-9a71-1fe5534d8ef5
