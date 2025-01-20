<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Data\GetPaginatedCustomerPaymentsQuery;

class GetPaginatedCustomerPaymentsQueryFactory extends OldFactory
{
    public function create(): GetPaginatedCustomerPaymentsQuery
    {
        return new GetPaginatedCustomerPaymentsQuery(
            SortablePaginatedQueryFactory::new($this->data)->create(),
            $this->get('profileId')
        );
    }
}
