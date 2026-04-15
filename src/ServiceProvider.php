<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\AggregateServiceProvider;
use Scrada\Scrada;

final class ServiceProvider extends AggregateServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ScradaManager::class);

        $this->app->bind(Scrada::class, fn () => $this->app[ScradaManager::class]->driver());
        $this->app->alias(Scrada::class, 'scrada');
    }

    public function boot(): void
    {
        $this->app['events']->listen(LocaleUpdated::class, UseLanguageWhenAppLocaleUpdated::class);
    }
}
