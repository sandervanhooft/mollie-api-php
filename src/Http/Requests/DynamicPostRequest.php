<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Contracts\HasPayload;
use Mollie\Api\Traits\HasJsonPayload;
use Mollie\Api\Types\Method;

/**
 * Only meant to be used to make the old Endpoints work with the new Request system.
 */
class DynamicPostRequest extends DynamicRequest implements HasPayload
{
    use HasJsonPayload;

    protected static string $method = Method::POST;

    private array $payload;

    private array $query = [];

    public function __construct(
        string $url,
        string $resourceClass = '',
        array $payload = [],
        array $query = []
    ) {
        parent::__construct($url, $resourceClass);

        $this->payload = $payload;
        $this->query = $query;
    }

    protected function defaultQuery(): array
    {
        return $this->query;
    }

    protected function defaultPayload(): array
    {
        return $this->payload;
    }
}
