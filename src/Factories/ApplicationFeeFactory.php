<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Data\ApplicationFee;

class ApplicationFeeFactory extends OldFactory
{
    public function create(): ApplicationFee
    {
        return new ApplicationFee(
            MoneyFactory::new($this->data['amount'])->create(),
            $this->data['description'],
        );
    }
}
