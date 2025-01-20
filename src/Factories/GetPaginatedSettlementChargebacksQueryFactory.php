<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Data\GetPaginatedSettlementChargebacksQuery;

class GetPaginatedSettlementChargebacksQueryFactory extends OldFactory
{
    /**
     * Create a new instance of GetPaginatedSettlementChargebacksQuery.
     */
    public function create(): GetPaginatedSettlementChargebacksQuery
    {
        return new GetPaginatedSettlementChargebacksQuery(
            PaginatedQueryFactory::new($this->data)->create(),
            $this->get('includePayment', false),
            $this->get('profileId')
        );
    }
}
