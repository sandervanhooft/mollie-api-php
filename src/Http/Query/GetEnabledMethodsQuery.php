<?php

namespace Mollie\Api\Http\Query;

use Mollie\Api\Helpers\Arr;
use Mollie\Api\Http\Payload\Money;
use Mollie\Api\Types\MethodQuery;

class GetEnabledMethodsQuery extends Query
{
    public function __construct(
        private string $sequenceType = MethodQuery::SEQUENCE_TYPE_ONEOFF,
        private string $resource = MethodQuery::RESOURCE_PAYMENTS,
        private ?string $locale = null,
        private ?Money $amount = null,
        private ?string $billingCountry = null,
        private ?array $includeWallets = null,
        private ?array $orderLineCategories = null,
        private ?string $profileId = null,
        private ?array $include = null,
    ) {}

    public function toArray(): array
    {
        return [
            'sequenceType' => $this->sequenceType,
            'locale' => $this->locale,
            'amount' => $this->amount?->data(),
            'resource' => $this->resource,
            'billingCountry' => $this->billingCountry,
            'includeWallets' => Arr::join($this->includeWallets),
            'orderLineCategories' => Arr::join($this->orderLineCategories),
            'profileId' => $this->profileId,
            'include' => Arr::join($this->include),
        ];
    }
}
