<?php declare(strict_types=1);

namespace Scrada\Laravel\Tests;

use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Event;
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
        Event::fake([RequestSending::class, ResponseReceived::class]);

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

            Event::assertDispatched(RequestSending::class,
                static fn (RequestSending $event) => str_starts_with($event->request->url(), 'https://api.scrada.be')
            );

            Event::assertDispatched(ResponseReceived::class,
                static fn (ResponseReceived $event) => $event->response->unauthorized()
            );
        }
    }
}
