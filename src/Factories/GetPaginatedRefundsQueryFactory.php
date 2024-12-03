<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Query\GetPaginatedRefundsQuery;
use Mollie\Api\Types\PaymentIncludesQuery;

class GetPaginatedRefundsQueryFactory extends Factory
{
    public function create(): GetPaginatedRefundsQuery
    {
        $embedPayment = $this->includes('embed', PaymentIncludesQuery::PAYMENT);

        return new GetPaginatedRefundsQuery(
            PaginatedQueryFactory::new($this->data)->create(),
            $this->get('embedPayment', $embedPayment),
            $this->get('profileId')
        );
    }
}
