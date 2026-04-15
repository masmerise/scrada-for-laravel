<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Foundation\Events\LocaleUpdated;

final readonly class UseLanguageWhenAppLocaleUpdated
{
    public function __construct(private ScradaManager $manager, private UseLanguage $use) {}

    public function handle(LocaleUpdated $event): void
    {
        foreach ($this->manager->getDrivers() as $scrada) {
            ($this->use)($scrada, $event->locale);
        }
    }
}
