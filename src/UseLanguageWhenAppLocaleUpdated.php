<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Illuminate\Foundation\Events\LocaleUpdated;
use Scrada\Scrada;

final readonly class UseLanguageWhenAppLocaleUpdated
{
    public function __construct(private Scrada $scrada, private UseLanguage $use) {}

    public function handle(LocaleUpdated $event): void
    {
        ($this->use)($this->scrada, $event->locale);
    }
}
