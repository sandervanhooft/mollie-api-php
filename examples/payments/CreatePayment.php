<?php

declare(strict_types=1);

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Payment;
use Mollie\examples\BaseExample;
use Mollie\examples\ExampleRepository;

class CreatePayment extends BaseExample
{
    /**
     * A minimalistic example on how to create a payment with the Mollie API.
     *
     * @param string $orderId
     * @param string $baseUrl
     * @return \Mollie\Api\Resources\Payment
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function exampleApiCall(string $orderId, string $baseUrl): Payment
    {
        return $this->mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00", // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => "Order #{$orderId}",
            "redirectUrl" => "{$baseUrl}/return.php?order_id={$orderId}",
            "webhookUrl" => "{$baseUrl}/webhook.php", // optional
            "metadata" => [ // optional
                "order_id" => $orderId,
            ],
        ]);
    }

    /**
     * A detailed example on how to integrate payment creation with the Mollie API.
     *
     * @return void
     */
    public function exampleIntegration(): void
    {
        try {
            /*
             * Generate a unique order id for this example. It is important to include this unique attribute
             * in the redirectUrl (below) so a proper return page can be shown to the customer.
             */
            $orderId = (string) time();

            $payment = $this->exampleApiCall($orderId, $this->getBaseUrl());

            /*
             * In this example we store the order with its payment status in a database.
             */
            ExampleRepository::storeOrderPaymentStatus($orderId, $payment->status);

            /*
             * Send the customer off to complete the payment.
             * This request should always be a GET, thus we enforce 303 http response code
             */
            $this->redirect($payment->getCheckoutUrl());
        } catch (ApiException $apiException) {
            echo "API call failed: " . $apiException->getMessage();
        }
    }
}
