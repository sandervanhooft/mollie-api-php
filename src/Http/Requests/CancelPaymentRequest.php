<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Resources\Payment;
use Mollie\Api\Types\Method;
use Mollie\Api\Contracts\SupportsTestmodeInQuery;

class CancelPaymentRequest extends ResourceHydratableRequest implements SupportsTestmodeInQuery
{
    protected static string $method = Method::DELETE;

    public static string $targetResourceClass = Payment::class;

    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function resolveResourcePath(): string
    {
        return "payments/{$this->id}";
    }
}
