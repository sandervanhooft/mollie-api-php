<?php

declare(strict_types=1);

namespace Mollie\examples;

class ExampleRepository
{
    public static function storeOrderPaymentStatus(string $orderId, string $status)
    {
        $orderId = intval($orderId);
        $database = dirname(__FILE__) . "/database/order-{$orderId}.txt";

        file_put_contents($database, $status);
    }
}