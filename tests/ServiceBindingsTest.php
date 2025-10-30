<?php declare(strict_types=1);

namespace Scrada\Laravel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Scrada\Scrada;

final class ServiceBindingsTest extends ScradaTestCase
{
    #[Test]
    public function exception_is_thrown_if_config_is_missing(): void
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The Scrada config is missing.');

        // Act
        $this->app[Scrada::class];
    }

    #[Test]
    public function exception_is_thrown_if_config_is_incomplete(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', []);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The Scrada config is missing the "api_key" key.');

        // Act
        $this->app[Scrada::class];
    }

    #[Test]
    public function scrada_can_be_resolved(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
            'password' => 'SajA1NOEphxVMwTT',
        ]);

        // Act
        $instance = $this->app[Scrada::class];

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }

    #[Test]
    public function scrada_can_be_resolved_using_alias(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
            'password' => 'SajA1NOEphxVMwTT',
        ]);

        // Act
        $instance = $this->app['scrada'];

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }

    #[Test]
    public function scrada_can_be_resolved_using_helper(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
            'password' => 'SajA1NOEphxVMwTT',
        ]);

        // Act
        $instance = scrada();

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }
}
