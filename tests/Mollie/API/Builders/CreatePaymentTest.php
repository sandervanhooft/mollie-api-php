<?php

declare(strict_types=1);

namespace Tests\Mollie\API\Builders;

use Mollie\Api\Builders\CreatePaymentBuilder;
use Mollie\Api\MollieApiClient;
use Tests\Mollie\Api\MollieApiClientTest;

class CreatePaymentTest extends MollieApiClientTest
{
    /** @test */
    public function it_works()
    {
        $createPayment = new CreatePaymentBuilder(new MollieApiClient);

        $createPayment
            ->description("Created a payment with full IDE autocompletion!")
            ->redirectUrl("https://www.sandorian.com")
            ->amount("10.00", "EUR")
            ->testmodeEnabled()
            ->webhookUrl("https://www.example-webhook.com/webhooks");

        $this->assertEquals([
            "description" => "Created a payment with full IDE autocompletion!",
            "redirectUrl" => "https://www.sandorian.com",
            "amount" => [
                "value" => "10.00",
                "currency" => "EUR",
            ],
            "testmode" => true,
            "webhookUrl" => "https://www.example-webhook.com/webhooks",
        ], $createPayment->getPayload());

        $this->assertEquals([], $createPayment->getFilters());
    }

    /** @test */
    public function can_add_routes()
    {
        $createPayment = new CreatePaymentBuilder(new MollieApiClient);

        $createPayment
            ->description("Created a payment with full IDE autocompletion!")
            ->redirectUrl("https://www.sandorian.com")
            ->amount("10.00", "EUR")
            ->testmodeEnabled()
            ->addRoute(
                '1.00',
                'EUR',
                [
                    'type' => 'organization',
                    'organizationId' => 'org_23456',
                ]
            )
            ->addRoute(
                '9.00',
                'EUR',
                [
                    'type' => 'organization',
                    'organizationId' => 'org_56789',
                ]
            );

        $this->assertEquals([
            "description" => "Created a payment with full IDE autocompletion!",
            "redirectUrl" => "https://www.sandorian.com",
            "amount" => [
                "value" => "10.00",
                "currency" => "EUR",
            ],
            "testmode" => true,
            "routing" => [
                [
                    'amount' => [
                        'value' => '1.00',
                        'currency' => 'EUR',
                    ],
                    'destination' => [
                        'type' => 'organization',
                        'organizationId' => 'org_23456',
                    ],
                ],
                [
                    'amount' => [
                        'value' => '9.00',
                        'currency' => 'EUR',
                    ],
                    'destination' => [
                        'type' => 'organization',
                        'organizationId' => 'org_56789',
                    ],
                ],
            ],
        ], $createPayment->getPayload());

        $this->assertEquals([], $createPayment->getFilters());
    }
}
