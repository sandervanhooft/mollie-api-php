<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Contracts\HasPayload;
use Mollie\Api\Http\Payload\CreateProfilePayload;
use Mollie\Api\Resources\Profile;
use Mollie\Api\Traits\HasJsonPayload;
use Mollie\Api\Types\Method;

class CreateProfileRequest extends ResourceHydratableRequest implements HasPayload
{
    use HasJsonPayload;

    /**
     * Define the HTTP method.
     */
    protected static string $method = Method::POST;

    /**
     * The resource class the request should be casted to.
     */
    public static string $targetResourceClass = Profile::class;

    private CreateProfilePayload $payload;

    public function __construct(CreateProfilePayload $payload)
    {
        parent::__construct();
        $this->payload = $payload;
    }

    protected function defaultPayload(): array
    {
        return $this->payload->toArray();
    }

    /**
     * The resource path.
     */
    public function resolveResourcePath(): string
    {
        return 'profiles';
    }
}
