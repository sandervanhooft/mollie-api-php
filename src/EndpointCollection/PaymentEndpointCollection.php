<?php

namespace Mollie\Api\EndpointCollection;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Factories\CreatePaymentPayloadFactory;
use Mollie\Api\Factories\CreateRefundPaymentPayloadFactory;
use Mollie\Api\Factories\GetPaymentQueryFactory;
use Mollie\Api\Factories\SortablePaginatedQueryFactory;
use Mollie\Api\Factories\UpdatePaymentPayloadFactory;
use Mollie\Api\Helpers;
use Mollie\Api\Helpers\Arr;
use Mollie\Api\Http\Payload\CreatePaymentPayload;
use Mollie\Api\Http\Payload\CreateRefundPaymentPayload;
use Mollie\Api\Http\Payload\UpdatePaymentPayload;
use Mollie\Api\Http\Query\CreatePaymentQuery;
use Mollie\Api\Http\Query\GetPaymentQuery;
use Mollie\Api\Http\Requests\CancelPaymentRequest;
use Mollie\Api\Http\Requests\CreatePaymentRefundRequest;
use Mollie\Api\Http\Requests\CreatePaymentRequest;
use Mollie\Api\Http\Requests\GetPaginatedPaymentsRequest;
use Mollie\Api\Http\Requests\GetPaymentRequest;
use Mollie\Api\Http\Requests\UpdatePaymentRequest;
use Mollie\Api\Resources\LazyCollection;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Resources\PaymentCollection;
use Mollie\Api\Resources\Refund;

class PaymentEndpointCollection extends EndpointCollection
{
    /**
     * Retrieve a single payment from Mollie.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     *
     *
     * @throws ApiException
     */
    public function get(string $id, $query = [], bool $testmode = false): Payment
    {
        if (! $query instanceof GetPaymentQuery) {
            $testmode = Helpers::extractBool($query, 'testmode', $testmode);
            $query = GetPaymentQueryFactory::new($query)
                ->create();
        }

        return $this->send((new GetPaymentRequest($id, $query))->test($testmode));
    }

    /**
     * Creates a payment in Mollie.
     *
     * @param  CreatePaymentPayload|array  $data  An array containing details on the payment.
     * @param  CreatePaymentQuery|array|string  $query  An array of strings or a single string containing the details to include.
     *
     * @throws ApiException
     */
    public function create($data = [], $query = [], bool $testmode = false): Payment
    {
        if (! $data instanceof CreatePaymentPayload) {
            $testmode = Helpers::extractBool($data, 'testmode', $testmode);
            $data = CreatePaymentPayloadFactory::new($data)
                ->create();
        }

        if (! $query instanceof CreatePaymentQuery) {
            $query = CreatePaymentQuery::fromArray(Arr::wrap($query));
        }

        /** @var Payment */
        return $this->send((new CreatePaymentRequest($data, $query))->test($testmode));
    }

    /**
     * Update the given Payment.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     *
     * @param  string  $id
     * @param  array|UpdatePaymentPayload  $data
     *
     * @throws ApiException
     */
    public function update($id, $data = [], bool $testmode = false): ?Payment
    {
        if (! $data instanceof UpdatePaymentPayload) {
            $testmode = Helpers::extractBool($data, 'testmode', $testmode);
            $data = UpdatePaymentPayloadFactory::new($data)
                ->create();
        }

        /** @var null|Payment */
        return $this->send((new UpdatePaymentRequest($id, $data))->test($testmode));
    }

    /**
     * Deletes the given Payment.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     * Returns with HTTP status No Content (204) if successful.
     *
     *
     * @throws ApiException
     */
    public function delete(string $id, $data = []): ?Payment
    {
        return $this->cancel($id, $data);
    }

    /**
     * Cancel the given Payment. This is just an alias of the 'delete' method.
     *
     * Will throw a ApiException if the payment id is invalid or the resource cannot be found.
     * Returns with HTTP status No Content (204) if successful.
     *
     * @param  array|bool  $data
     *
     * @throws ApiException
     */
    public function cancel(string $id, $data = []): ?Payment
    {
        $testmode = Helpers::extractBool($data, 'testmode', false);

        /** @var null|Payment */
        return $this->send((new CancelPaymentRequest($id))->test($testmode));
    }

    /**
     * Issue a refund for the given payment.
     *
     * The $data parameter may either be an array of endpoint
     * parameters, or an instance of CreateRefundPaymentData.
     *
     * @param  array|CreateRefundPaymentPayload  $payload
     *
     * @throws ApiException
     */
    public function refund(Payment $payment, $payload = [], bool $testmode = false): Refund
    {
        if (! $payload instanceof CreateRefundPaymentPayload) {
            $testmode = Helpers::extractBool($payload, 'testmode', $testmode);
            $payload = CreateRefundPaymentPayloadFactory::new($payload)
                ->create();
        }

        return $this->send((new CreatePaymentRefundRequest(
            $payment->id,
            $payload
        ))->test($testmode));
    }

    /**
     * Get the balance endpoint.
     */
    public function page(?string $from = null, ?int $limit = null, array $filters = []): PaymentCollection
    {
        $testmode = Helpers::extractBool($filters, 'testmode', false);
        $query = SortablePaginatedQueryFactory::new([
            'from' => $from,
            'limit' => $limit,
            'filters' => $filters,
        ])->create();

        return $this->send((new GetPaginatedPaymentsRequest($query))->test($testmode));
    }

    /**
     * Create an iterator for iterating over payments retrieved from Mollie.
     *
     * @param  string  $from  The first resource ID you want to include in your list.
     * @param  bool  $iterateBackwards  Set to true for reverse order iteration (default is false).
     */
    public function iterator(?string $from = null, ?int $limit = null, array $filters = [], bool $iterateBackwards = false): LazyCollection
    {
        $testmode = Helpers::extractBool($filters, 'testmode', false);
        $query = SortablePaginatedQueryFactory::new([
            'from' => $from,
            'limit' => $limit,
            'filters' => $filters,
        ])->create();

        return $this->send(
            (new GetPaginatedPaymentsRequest($query))
                ->useIterator()
                ->setIterationDirection($iterateBackwards)
                ->test($testmode)
        );
    }
}
