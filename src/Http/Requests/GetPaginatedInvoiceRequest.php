<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Contracts\IsIteratable;
use Mollie\Api\Resources\InvoiceCollection;
use Mollie\Api\Traits\IsIteratableRequest;

class GetPaginatedInvoiceRequest extends PaginatedRequest implements IsIteratable
{
    use IsIteratableRequest;

    /**
     * The resource class the request should be casted to.
     */
    public static string $targetResourceClass = InvoiceCollection::class;

    public function resolveResourcePath(): string
    {
        return 'invoices';
    }
}
