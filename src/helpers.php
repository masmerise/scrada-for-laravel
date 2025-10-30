<?php declare(strict_types=1);

use Scrada\Scrada;

if (! function_exists('scrada')) {
    function scrada(): Scrada
    {
        return app('scrada');
    }
}
