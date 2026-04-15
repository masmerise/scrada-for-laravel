<?php declare(strict_types=1);

namespace Scrada\Laravel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Scrada\Laravel\ScradaManager;
use Scrada\Scrada;

final class ServiceBindingsTest extends ScradaTestCase
{
    #[Test]
    public function exception_is_thrown_if_company_config_is_missing(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'default' => 'acme',
            'companies' => [],
        ]);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The Scrada config for company "acme" is missing.');

        // Act
        $this->app[ScradaManager::class]->company('acme');
    }

    #[Test]
    public function exception_is_thrown_if_company_config_is_incomplete(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'default' => 'acme',
            'companies' => [
                'acme' => [],
            ],
        ]);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The Scrada config for company "acme" is missing the "api_key" key.');

        // Act
        $this->app[ScradaManager::class]->company('acme');
    }

    #[Test]
    public function manager_can_be_resolved(): void
    {
        // Act
        $instance = $this->app[ScradaManager::class];

        // Assert
        $this->assertInstanceOf(ScradaManager::class, $instance);
    }

    #[Test]
    public function scrada_can_be_resolved(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'default' => 'acme',
            'companies' => [
                'acme' => [
                    'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
                    'password' => 'SajA1NOEphxVMwTT',
                ],
            ],
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
            'default' => 'acme',
            'companies' => [
                'acme' => [
                    'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
                    'password' => 'SajA1NOEphxVMwTT',
                ],
            ],
        ]);

        // Act
        $instance = $this->app['scrada'];

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }

    #[Test]
    public function company_can_be_resolved_from_manager(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'default' => 'acme',
            'companies' => [
                'acme' => [
                    'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
                    'password' => 'SajA1NOEphxVMwTT',
                ],
            ],
        ]);

        // Act
        $instance = $this->app[ScradaManager::class]->company('acme');

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }

    #[Test]
    public function scrada_can_be_resolved_using_helper(): void
    {
        // Arrange
        $this->app['config']->set('services.scrada', [
            'default' => 'acme',
            'companies' => [
                'acme' => [
                    'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
                    'password' => 'SajA1NOEphxVMwTT',
                ],
            ],
        ]);

        // Act
        $instance = scrada();

        // Assert
        $this->assertInstanceOf(Scrada::class, $instance);
    }
}
