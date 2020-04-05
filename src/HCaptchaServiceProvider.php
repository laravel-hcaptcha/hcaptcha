<?php


namespace LaravelHCaptcha\HCaptcha;

use Arcanedev\Support\Providers\PackageServiceProvider as ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use LaravelHCaptcha\HCaptcha\Contracts\HCapchaContract;
use LaravelHCaptcha\HCaptcha\Contracts\HCaptchaManagerContract;

class HCaptchaServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'hcaptcha';


    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();
        $this->registerNoCaptchaManager();
    }


    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            HCapchaContract::class,
            HCaptchaManagerContract::class,
        ];
    }

    private function registerNoCaptchaManager(): void
    {
        $this->singleton(HCaptchaManagerContract::class, HCaptchaManager::class);

        $this->bind(HCapchaContract::class, function (Application $app) {
            /** @var  Repository $config */
            $config = $app['config'];

            return $app->make(HCaptchaManagerContract::class)->setDriver($config->get('hcaptcha.driver'));
        });
    }
}
