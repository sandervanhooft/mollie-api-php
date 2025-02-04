<?php

namespace Mollie\Api\EndpointCollection;

use Mollie\Api\Exceptions\RequestException;
use Mollie\Api\Factories\CreateMandateRequestFactory;
use Mollie\Api\Http\Requests\GetMandateRequest;
use Mollie\Api\Http\Requests\GetPaginatedMandateRequest;
use Mollie\Api\Http\Requests\RevokeMandateRequest;
use Mollie\Api\Resources\Customer;
use Mollie\Api\Resources\LazyCollection;
use Mollie\Api\Resources\Mandate;
use Mollie\Api\Resources\MandateCollection;
use Mollie\Api\Utils\Utility;

class MandateEndpointCollection extends EndpointCollection
{
    /**
     * Creates a mandate for a specific customer.
     *
     *
     * @throws RequestException
     */
    public function createForCustomer(Customer $customer, array $payload = [], bool $testmode = false): Mandate
    {
        return $this->createForCustomerId($customer->id, $payload, $testmode);
    }

    /**
     * Creates a mandate for a specific customer ID.
     *
     *
     * @throws RequestException
     */
    public function createForCustomerId(string $customerId, array $payload = [], bool $testmode = false): Mandate
    {
        $testmode = Utility::extractBool($payload, 'testmode', $testmode);

        $request = CreateMandateRequestFactory::new($customerId)
            ->withPayload($payload)
            ->create();

        /** @var Mandate */
        return $this->send($request->test($testmode));
    }

    /**
     * Retrieve a specific mandate for a customer.
     *
     *
     * @throws RequestException
     */
    public function getForCustomer(Customer $customer, string $mandateId, array $parameters = []): Mandate
    {
        return $this->getForCustomerId($customer->id, $mandateId, $parameters);
    }

    /**
     * Retrieve a specific mandate for a customer ID.
     *
     * @param  bool|array  $testmode
     *
     * @throws RequestException
     */
    public function getForCustomerId(string $customerId, string $mandateId, $testmode = false): Mandate
    {
        $testmode = Utility::extractBool($testmode, 'testmode', false);

        /** @var Mandate */
        return $this->send((new GetMandateRequest($customerId, $mandateId))->test($testmode));
    }

    /**
     * Revoke a mandate for a specific customer.
     *
     *
     * @throws RequestException
     */
    public function revokeForCustomer(Customer $customer, string $mandateId, $data = []): void
    {
        $this->revokeForCustomerId($customer->id, $mandateId, $data);
    }

    /**
     * Revoke a mandate for a specific customer ID.
     *
     * @param  bool|array  $testmode
     *
     * @throws RequestException
     */
    public function revokeForCustomerId(string $customerId, string $mandateId, $testmode = false): void
    {
        $testmode = Utility::extractBool($testmode, 'testmode', false);

        $this->send((new RevokeMandateRequest($customerId, $mandateId))->test($testmode));
    }

    /**
     * Retrieves a collection of mandates for the given customer.
     *
     * @param  string  $from  The first mandate ID you want to include in your list.
     * @param  bool|array  $testmode
     *
     * @throws RequestException
     */
    public function pageForCustomer(Customer $customer, ?string $from = null, ?int $limit = null, $testmode = false): MandateCollection
    {
        return $this->pageForCustomerId($customer->id, $from, $limit, $testmode);
    }

    /**
     * Retrieves a collection of mandates for the given customer ID.
     *
     * @param  string  $from  The first mandate ID you want to include in your list.
     * @param  bool|array  $testmode
     *
     * @throws RequestException
     */
    public function pageForCustomerId(string $customerId, ?string $from = null, ?int $limit = null, $testmode = false): MandateCollection
    {
        $testmode = Utility::extractBool($testmode, 'testmode', false);

        /** @var MandateCollection */
        return $this->send(
            (new GetPaginatedMandateRequest($customerId, $from, $limit))->test($testmode)
        );
    }

    /**
     * Create an iterator for iterating over mandates for the given customer.
     *
     * @param  string  $from  The first mandate ID you want to include in your list.
     * @param  bool|array  $testmode
     * @param  bool  $iterateBackwards  Set to true for reverse order iteration (default is false).
     */
    public function iteratorForCustomer(
        Customer $customer,
        ?string $from = null,
        ?int $limit = null,
        $testmode = false,
        bool $iterateBackwards = false
    ): LazyCollection {
        return $this->iteratorForCustomerId($customer->id, $from, $limit, $testmode, $iterateBackwards);
    }

    /**
     * Create an iterator for iterating over mandates for the given customer ID.
     *
     * @param  string  $from  The first mandate ID you want to include in your list.
     * @param  bool|array  $testmode
     * @param  bool  $iterateBackwards  Set to true for reverse order iteration (default is false).
     */
    public function iteratorForCustomerId(
        string $customerId,
        ?string $from = null,
        ?int $limit = null,
        $testmode = false,
        bool $iterateBackwards = false
    ): LazyCollection {
        $testmode = Utility::extractBool($testmode, 'testmode', false);

        return $this->send(
            (new GetPaginatedMandateRequest($customerId, $from, $limit))
                ->useIterator()
                ->setIterationDirection($iterateBackwards)
                ->test($testmode)
        );
    }
}
