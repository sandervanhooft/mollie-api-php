<?php

namespace Mollie\Api\Factories;

use Mollie\Api\Utils\Utility;

abstract class RequestFactory extends Factory
{
    private array $payload = [];

    private array $query = [];

    public function withPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function withQuery(array $query): static
    {
        $this->query = $query;

        return $this;
    }

    protected function payload(string $key, $default = null)
    {
        return $this->get($key, $default, $this->payload);
    }

    protected function query(string $key, $default = null)
    {
        return $this->get($key, $default, $this->query);
    }

    protected function payloadIncludes(string $key, $value)
    {
        return $this->includes($key, $value, $this->payload);
    }

    protected function queryIncludes(string $key, $value)
    {
        return $this->includes($key, $value, $this->query);
    }

    protected function payloadHas($key): bool
    {
        return $this->has($key, $this->payload);
    }

    /**
     * @param  string|array<string>  $key
     */
    protected function queryHas($key): bool
    {
        return $this->has($key, $this->query);
    }

    protected function transformFromPayload($key, $composable)
    {
        return $this->transformFromResolved($this->payload($key), $composable);
    }

    protected function transformFromQuery($key, $composable)
    {
        return $this->transformFromResolved($this->query($key), $composable);
    }

    /**
     * Map a value to a new form if it is not null.
     */
    protected function transformFromResolved($resolvedValue, $composable)
    {
        return Utility::compose($resolvedValue, $composable);
    }
}
