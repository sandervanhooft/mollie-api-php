<?php

namespace Tests\Http\Requests;

use Mollie\Api\Http\Requests\DynamicGetRequest;
use Mollie\Api\Http\Requests\GetPaginatedMandateRequest;
use Mollie\Api\Resources\Mandate;
use Mollie\Api\Resources\MandateCollection;
use PHPUnit\Framework\TestCase;
use Mollie\Api\Fake\MockMollieClient;
use Mollie\Api\Fake\MockResponse;
use Mollie\Api\Fake\SequenceMockResponse;

class GetPaginatedMandateRequestTest extends TestCase
{
    /** @test */
    public function it_can_get_paginated_mandates()
    {
        $client = new MockMollieClient([
            GetPaginatedMandateRequest::class => new MockResponse(200, 'mandate-list'),
        ]);

        $request = new GetPaginatedMandateRequest('cst_kEn1PlbGa');

        /** @var MandateCollection */
        $mandates = $client->send($request);

        $this->assertTrue($mandates->getResponse()->successful());
        $this->assertInstanceOf(MandateCollection::class, $mandates);
        $this->assertGreaterThan(0, $mandates->count());

        foreach ($mandates as $mandate) {
            $this->assertInstanceOf(Mandate::class, $mandate);
            $this->assertEquals('mandate', $mandate->resource);
        }
    }

    /** @test */
    public function it_can_iterate_over_mandates()
    {
        $client = new MockMollieClient([
            GetPaginatedMandateRequest::class => new MockResponse(200, 'mandate-list'),
            DynamicGetRequest::class => new SequenceMockResponse(
                new MockResponse(200, 'mandate-list'),
                new MockResponse(200, 'empty-list', 'mandates'),
            ),
        ]);

        $request = (new GetPaginatedMandateRequest('cst_kEn1PlbGa'))->useIterator();

        /** @var MandateCollection */
        $mandates = $client->send($request);
        $this->assertTrue($mandates->getResponse()->successful());

        foreach ($mandates as $mandate) {
            $this->assertInstanceOf(Mandate::class, $mandate);
        }

        $client->assertSentCount(3);
    }

    /** @test */
    public function it_resolves_correct_resource_path()
    {
        $customerId = 'cst_kEn1PlbGa';
        $request = new GetPaginatedMandateRequest($customerId);

        $this->assertEquals("customers/{$customerId}/mandates", $request->resolveResourcePath());
    }
}
