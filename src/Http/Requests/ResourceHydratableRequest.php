<?php

namespace Mollie\Api\Http\Requests;

use Mollie\Api\Http\Request;
use Mollie\Api\Resources\WrapResource;

abstract class ResourceHydratableRequest extends Request
{
    /**
     * The original resource class the request should be hydrated into.
     *
     * @var string|null
     */
    protected $hydratableResource = null;

    /**
     * The custom resource class the request should be hydrated into.
     *
     * @var string|null|WrapResource
     */
    protected $customHydratableResource = null;

    public function isHydratable(): bool
    {
        return $this->hydratableResource !== null || $this->customHydratableResource !== null;
    }

    /**
     * @return string|WrapResource
     */
    public function getHydratableResource()
    {
        if (! $this->isHydratable()) {
            throw new \RuntimeException('Resource class is not set.');
        }

        return $this->customHydratableResource ?? $this->hydratableResource;
    }

    /**
     * @param  string|WrapResource  $hydratableResource
     */
    public function setHydratableResource($hydratableResource): self
    {
        $this->customHydratableResource = $hydratableResource;

        return $this;
    }

    public function resetHydratableResource(): self
    {
        $this->customHydratableResource = null;

        return $this;
    }
}
