<?php declare(strict_types=1);

namespace Scrada\Laravel\Tests;

use PHPUnit\Framework\Attributes\Test;
use Scrada\Authentication\Failure\CouldNotAuthenticate;
use Scrada\Company\Type\Primitive\CompanyId;
use Scrada\Scrada;
use Throwable;

final class SmokeTest extends ScradaTestCase
{
    #[Test]
    public function execute(): void
    {
        // Setup
        $this->app['config']->set('services.scrada', [
            'api_key' => '4844a45c-33d1-4937-83f4-366d36449eaf',
            'password' => 'SajA1NOEphxVMwTT',
        ]);

        // Run
        try {
            $this->app[Scrada::class]->company->get(CompanyId::fromString('0f83133b-eadd-41a2-9879-a2dbd522c381'));

            $this->fail('Should have thrown an authentication exception.');
        } catch (Throwable $ex) {
            $this->assertInstanceOf(CouldNotAuthenticate::class, $ex);
        }
    }
}
