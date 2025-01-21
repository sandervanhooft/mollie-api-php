<?php

namespace Mollie\Api\Traits;

use Mollie\Api\Http\PendingRequest;
use Mollie\Api\Http\Response;
use Mollie\Api\Exceptions\MollieException;

trait HasResponse
{
    protected ?Response $response = null;

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getPendingRequest(): PendingRequest
    {
        if (! $this->response) {
            throw new MollieException('Response is not set');
        }

        return $this->response->getPendingRequest();
    }
}
