<?php

namespace Tests\EndpointCollection;

use Mollie\Api\Http\Requests\UpdatePaymentRouteRequest;
use Mollie\Api\Resources\Route;
use Tests\Fixtures\MockClient;
use Tests\Fixtures\MockResponse;
use Tests\TestCase;

class PaymentRouteEndpointCollectionTest extends TestCase
{
    /** @test */
    public function update_release_date_for()
    {
        $client = new MockClient([
            UpdatePaymentRouteRequest::class => new MockResponse(200, 'route'),
        ]);

        /** @var Route $route */
        $route = $client->paymentRoutes->updateReleaseDateForId('tr_7UhSN1zuXS', 'rt_abc123', '2024-01-01');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('route', $route->resource);
        $this->assertNotEmpty($route->id);
        $this->assertNotEmpty($route->amount);
        $this->assertNotEmpty($route->destination);
        $this->assertNotEmpty($route->releaseDate);
    }
}
