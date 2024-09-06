<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Contracts\HasPayload;
use Mollie\Api\Contracts\SupportsTestmode;
use Mollie\Api\Http\Payload\CreateRefundPaymentPayload;
use Mollie\Api\Http\Request;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Rules\Id;
use Mollie\Api\Traits\HasJsonPayload;
use Mollie\Api\Types\Method;

class CreateRefundPaymentRequest extends Request implements HasPayload, SupportsTestmode
{
    use HasJsonPayload;

    /**
     * Define the HTTP method.
     */
    protected static string $method = Method::POST;

    /**
     * The resource class the request should be casted to.
     */
    public static string $targetResourceClass = \Mollie\Api\Resources\Refund::class;

    private string $paymentId;

    private CreateRefundPaymentPayload $payload;

    public function __construct(
        string $identifier,
        CreateRefundPaymentPayload $payload
    ) {
        $this->paymentId = $identifier;
        $this->payload = $payload;
    }

    protected function defaultPayload(): array
    {
        return $this->payload->toArray();
    }

    public function rules(): array
    {
        return [
            'id' => Id::startsWithPrefix(Payment::$resourceIdPrefix),
        ];
    }

    public function resolveResourcePath(): string
    {
        return "payments/{$this->paymentId}/refunds";
    }
}
