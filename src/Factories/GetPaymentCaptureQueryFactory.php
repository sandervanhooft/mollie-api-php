<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Data\GetPaymentCaptureQuery;
use Mollie\Api\Types\PaymentIncludesQuery;

class GetPaymentCaptureQueryFactory extends OldFactory
{
    public function create(): GetPaymentCaptureQuery
    {
        $includePayment = $this->includes('include', PaymentIncludesQuery::PAYMENT);

        return new GetPaymentCaptureQuery(
            $this->get('includePayment', $includePayment),
        );
    }
}
