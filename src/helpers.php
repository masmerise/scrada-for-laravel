<?php declare(strict_types=1);

use Scrada\Laravel\ScradaManager;
use Scrada\Scrada;

if (! function_exists('scrada')) {
    function scrada(?string $company = null): Scrada
    {
        return app(ScradaManager::class)->company($company);
    }
}
