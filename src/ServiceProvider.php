<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\AggregateServiceProvider;
use InvalidArgumentException;
use Saloon\Config;
use Saloon\HttpSender\HttpSender;
use Scrada\Authentication\Credentials;
use Scrada\Scrada;
use Webmozart\Assert\Assert;

final class ServiceProvider extends AggregateServiceProvider
{
    public function boot(): void
    {
        if (! class_exists('Saloon\Laravel\SaloonServiceProvider')) {
            Config::$defaultSender = HttpSender::class;
        }
    }

    public function register(): void
    {
        $this->app->singleton(Scrada::class, $this->createScrada(...));
        $this->app->alias(Scrada::class, 'scrada');
    }

    /** @throws InvalidArgumentException */
    private function createScrada(Application $app): Scrada
    {
        /** Authentication */
        $scrada = $this->credentials($app['config']->get('services.scrada'));
        $scrada = Scrada::authenticate($scrada);

        /** Rate Limiting */
        $scrada->withStore($app['cache.store']);

        /** Localization */
        $app[UseLanguage::class]($scrada, $app->getLocale());

        $app['events']->listen(LocaleUpdated::class, UseLanguageWhenAppLocaleUpdated::class);

        return $scrada;
    }

    /** @throws InvalidArgumentException */
    private function credentials(mixed $config): Credentials
    {
        Assert::notNull($config, 'The Scrada config is missing.');
        Assert::isArray($config, 'The Scrada config must be an array.');
        Assert::keyExists($config, 'api_key', 'The Scrada config is missing the "api_key" key.');
        Assert::string($config['api_key'], 'The Scrada config is invalid and the "api_key" key must be a string.');
        Assert::keyExists($config, 'password', 'The Scrada config is missing the "password" key.');
        Assert::string($config['password'], 'The Scrada config is invalid and the "password" key must be a string.');

        /** @var array{api_key: string, password: string} $config */
        return Credentials::present(
            key: $config['api_key'],
            password: $config['password'],
        );
    }
}
