<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Http\Data\DataCollection;

class InvoiceLineCollectionFactory extends Factory
{
    public function create(): DataCollection
    {
        return new DataCollection(array_map(
            fn (array $item) => InvoiceLineFactory::new($item)->create(),
            $this->data
        ));
    }
}
