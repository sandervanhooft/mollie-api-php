<?php

declare(strict_types=1);

namespace Tests\Mollie\API\Builders;

use Mollie\Api\Builders\CreatePayment;
use Mollie\Api\MollieApiClient;
use Tests\Mollie\Api\MollieApiClientTest;

class CreatePaymentTest extends MollieApiClientTest
{
    /** @test */
    public function itDestructs()
    {
        $createPayment = new CreatePayment(new MollieApiClient);

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

}