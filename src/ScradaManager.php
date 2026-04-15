<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Scrada\Scrada;
use Webmozart\Assert\Assert;

/** @mixin Scrada */
final class ScradaManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->string('services.scrada.default');
    }

    public function company(?string $id = null): Scrada
    {
        return $this->driver($id);
    }

    /** @throws InvalidArgumentException */
    protected function createDriver($driver): Scrada
    {
        $config = $this->config($driver);

        /** Authentication */
        $scrada = Scrada::authenticate($config->credentials());

        /** Environment */
        if ($config->wantsTestEnv()) {
            $scrada->useTest();
        }

        /** Rate Limiting */
        $scrada->withStore($this->container['cache.store']);

        /** Localization */
        $this->container[UseLanguage::class]($scrada, $this->container->getLocale());

        return $scrada;
    }

    /** @throws InvalidArgumentException */
    private function config(string $company): Config
    {
        $config = $this->config->get("services.scrada.companies.{$company}");

        Assert::notNull($config, "The Scrada config for company \"{$company}\" is missing.");
        Assert::isArray($config, "The Scrada config for company \"{$company}\" must be an array.");
        Assert::keyExists($config, 'api_key', "The Scrada config for company \"{$company}\" is missing the \"api_key\" key.");
        Assert::string($config['api_key'], "The Scrada config for company \"{$company}\" is invalid and the \"api_key\" key must be a string.");
        Assert::keyExists($config, 'password', "The Scrada config for company \"{$company}\" is missing the \"password\" key.");
        Assert::string($config['password'], "The Scrada config for company \"{$company}\" is invalid and the \"password\" key must be a string.");

        return new Config(
            key: $config['api_key'],
            password: $config['password'],
            env: $config['env'] ?? 'production',
        );
    }
}
