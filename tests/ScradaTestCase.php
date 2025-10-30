<?php declare(strict_types=1);

namespace Scrada\Laravel\Tests;

use Orchestra\Testbench\TestCase;
use Scrada\Laravel\ServiceProvider;

abstract class ScradaTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
