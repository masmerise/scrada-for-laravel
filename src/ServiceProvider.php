<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\AggregateServiceProvider;
use InvalidArgumentException;
use Scrada\Scrada;
use Webmozart\Assert\Assert;

final class ServiceProvider extends AggregateServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Scrada::class, $this->createScrada(...));
        $this->app->alias(Scrada::class, 'scrada');
    }

    /** @throws InvalidArgumentException */
    protected function createScrada(Application $app): Scrada
    {
        $config = $this->config($app['config']);

        /** Authentication */
        $scrada = Scrada::authenticate($config->credentials());

        /** Environment */
        if ($config->wantsTestEnv()) {
            $scrada->useTest();
        }

        /** Rate Limiting */
        $scrada->withStore($app['cache.store']);

        /** Localization */
        $app[UseLanguage::class]($scrada, $app->getLocale());

        $app['events']->listen(LocaleUpdated::class, UseLanguageWhenAppLocaleUpdated::class);

        return $scrada;
    }

    /** @throws InvalidArgumentException */
    private function config(Repository $config): Config
    {
        $scrada = $config->get('services.scrada');

        Assert::notNull($scrada, 'The Scrada config is missing.');
        Assert::isArray($scrada, 'The Scrada config must be an array.');
        Assert::keyExists($scrada, 'api_key', 'The Scrada config is missing the "api_key" key.');
        Assert::string($scrada['api_key'], 'The Scrada config is invalid and the "api_key" key must be a string.');
        Assert::keyExists($scrada, 'password', 'The Scrada config is missing the "password" key.');
        Assert::string($scrada['password'], 'The Scrada config is invalid and the "password" key must be a string.');

        return new Config(
            key: $scrada['api_key'],
            password: $scrada['password'],
            env: $scrada['env'] ?? 'production',
        );
    }
}
