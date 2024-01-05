<?php

namespace Mollie\Api\Endpoints;

use Mollie\Api\Resources\LazyCollection;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Resources\Refund;
use Mollie\Api\Resources\RefundCollection;

class PaymentRefundEndpoint extends CollectionRestEndpoint
{
    protected string $resourcePath = "payments_refunds";

    /**
     * @inheritDoc
     */
    protected function getResourceObject(): Refund
    {
        return new Refund($this->client);
    }

    /**
     * @inheritDoc
     */
    protected function getResourceCollectionObject(int $count, object $_links): RefundCollection
    {
        return new RefundCollection($this->client, $count, $_links);
    }

    /**
     * @param Payment $payment
     * @param string $refundId
     * @param array $parameters
     *
     * @return Refund
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function getFor(Payment $payment, $refundId, array $parameters = []): Refund
    {
        return $this->getForId($payment->id, $refundId, $parameters);
    }

    /**
     * @param string $paymentId
     * @param string $refundId
     * @param array $parameters
     *
     * @return \Mollie\Api\Resources\Refund
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function getForId(string $paymentId, $refundId, array $parameters = []): Refund
    {
        $this->parentId = $paymentId;

        return parent::rest_read($refundId, $parameters);
    }

    /**
     * @param Payment $payment
     * @param array $parameters
     *
     * @return RefundCollection
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function listFor(Payment $payment, array $parameters = []): RefundCollection
    {
        return $this->listForId($payment->id, $parameters);
    }

    /**
     * Create an iterator for iterating over refunds for the given payment, retrieved from Mollie.
     *
     * @param Payment $payment
     * @param string $from The first resource ID you want to include in your list.
     * @param int $limit
     * @param array $parameters
     * @param bool $iterateBackwards Set to true for reverse order iteration (default is false).
     *
     * @return LazyCollection
     */
    public function iteratorFor(
        Payment $payment,
        ?string $from = null,
        ?int $limit = null,
        array $parameters = [],
        bool $iterateBackwards = false
    ): LazyCollection {
        return $this->iteratorForId($payment->id, $from, $limit, $parameters, $iterateBackwards);
    }

    /**
     * @param string $paymentId
     * @param array $parameters
     *
     * @return RefundCollection
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function listForId(string $paymentId, array $parameters = []): RefundCollection
    {
        $this->parentId = $paymentId;

        return parent::rest_list(null, null, $parameters);
    }

    /**
     * Create an iterator for iterating over refunds for the given payment id, retrieved from Mollie.
     *
     * @param string $paymentId
     * @param string $from The first resource ID you want to include in your list.
     * @param int $limit
     * @param array $parameters
     * @param bool $iterateBackwards Set to true for reverse order iteration (default is false).
     *
     * @return LazyCollection
     */
    public function iteratorForId(
        string $paymentId,
        ?string $from = null,
        ?int $limit = null,
        array $parameters = [],
        bool $iterateBackwards = false
    ): LazyCollection {
        $this->parentId = $paymentId;

        return $this->rest_iterator($from, $limit, $parameters, $iterateBackwards);
    }


    /**
     * Creates a refund for a specific payment.
     *
     * @param Payment $payment
     * @param array $data
     * @param array $filters
     *
     * @return Refund
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function createFor(Payment $payment, array $data, array $filters = []): Refund
    {
        return $this->createForId($payment->id, $data, $filters);
    }

    /**
     * Creates a refund for a specific payment.
     *
     * @param string $paymentId
     * @param array $data
     * @param array $filters
     *
     * @return \Mollie\Api\Resources\Refund
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function createForId(string $paymentId, array $data, array $filters = []): Refund
    {
        $this->parentId = $paymentId;

        return parent::rest_create($data, $filters);
    }
}
