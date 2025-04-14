<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Http\Data\Money;
use Mollie\Api\Resources\MethodCollection;
use Mollie\Api\Types\Method as HttpMethod;
use Mollie\Api\Types\MethodQuery;

class GetAllMethodsRequest extends ResourceHydratableRequest
{
    /**
     * Define the HTTP method.
     */
    protected static string $method = HttpMethod::GET;

    /**
     * The resource class the request should be casted to.
     */
    protected $hydratableResource = MethodCollection::class;

    private bool $includeIssuers;

    private bool $includePricing;

    private ?string $locale;

    private ?Money $amount;

    public function __construct(bool $includeIssuers = false, bool $includePricing = false, ?string $locale = null, ?Money $amount = null)
    {
        $this->includeIssuers = $includeIssuers;
        $this->includePricing = $includePricing;
        $this->locale = $locale;
        $this->amount = $amount;
    }

    protected function defaultQuery(): array
    {
        return [
            'include' => array_filter([
                $this->includeIssuers ? MethodQuery::INCLUDE_ISSUERS : null,
                $this->includePricing ? MethodQuery::INCLUDE_PRICING : null,
            ]),
            'locale' => $this->locale,
            'amount' => $this->amount,
        ];
    }

    public function resolveResourcePath(): string
    {
        return 'methods/all';
    }
}
