<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Scrada\Scrada;

final readonly class UseLanguage
{
    public function __invoke(Scrada $scrada, string $locale): void
    {
        match (strtolower($locale)) {
            'fr' => $scrada->useFrench(),
            'nl' => $scrada->useDutch(),
            default => $scrada->useEnglish(),
        };
    }
}
