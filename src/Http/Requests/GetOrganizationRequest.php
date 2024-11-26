<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Contracts\SupportsTestmodeInQuery;
use Mollie\Api\Resources\Organization;
use Mollie\Api\Types\Method;

class GetOrganizationRequest extends ResourceHydratableRequest implements SupportsTestmodeInQuery
{
    protected static string $method = Method::GET;

    public static string $targetResourceClass = Organization::class;

    public function resolveResourcePath(): string
    {
        return 'organizations';
    }
}
