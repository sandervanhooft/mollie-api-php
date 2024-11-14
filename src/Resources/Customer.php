<?php

namespace Mollie\Api\Resources;

use Mollie\Api\Exceptions\ApiException;

class Customer extends BaseResource
{
    use HasPresetOptions;

    public static string $resourceIdPrefix = 'cst_';

    /**
     * Id of the customer.
     *
     * @var string
     */
    public $id;

    /**
     * Either "live" or "test". Indicates this being a test or a live (verified) customer.
     *
     * @var string
     */
    public $mode;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string|null
     */
    public $locale;

    /**
     * @var \stdClass|mixed|null
     */
    public $metadata;

    /**
     * @var string[]|array
     */
    public $recentlyUsedMethods;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var \stdClass
     */
    public $_links;

    /**
     * @throws ApiException
     */
    public function update(): ?Customer
    {
        $body = [
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
            'metadata' => $this->metadata,
        ];

        /** @var null|Customer */
        return $this->connector->customers->update($this->id, $body);
    }

    /**
     * @return Payment
     *
     * @throws ApiException
     */
    public function createPayment(array $options = [], array $filters = [])
    {
        return $this->connector->customerPayments->createFor($this, $this->withPresetOptions($options), $filters);
    }

    /**
     * Get all payments for this customer
     *
     * @return PaymentCollection
     *
     * @throws ApiException
     */
    public function payments()
    {
        return $this->connector->customerPayments->listFor($this, null, null, $this->getPresetOptions());
    }

    /**
     * @return Subscription
     *
     * @throws ApiException
     */
    public function createSubscription(array $options = [], array $filters = [])
    {
        return $this->connector->subscriptions->createFor($this, $this->withPresetOptions($options), $filters);
    }

    /**
     * @param  string  $subscriptionId
     * @return Subscription
     *
     * @throws ApiException
     */
    public function getSubscription($subscriptionId, array $parameters = [])
    {
        return $this->connector->subscriptions->getFor($this, $subscriptionId, $this->withPresetOptions($parameters));
    }

    /**
     * @param string $subscriptionId
     *
     * @return \Mollie\Api\Resources\Subscription
     * @throws ApiException
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->connector->subscriptions->cancelFor($this, $subscriptionId, $this->getPresetOptions());
    }

    /**
     * Get all subscriptions for this customer
     *
     * @return SubscriptionCollection
     *
     * @throws ApiException
     */
    public function subscriptions()
    {
        return $this->connector->subscriptions->listFor($this, null, null, $this->getPresetOptions());
    }

    /**
     * @return Mandate
     *
     * @throws ApiException
     */
    public function createMandate(array $options = [], array $filters = [])
    {
        return $this->connector->mandates->createFor($this, $this->withPresetOptions($options), $filters);
    }

    /**
     * @param  string  $mandateId
     * @return Mandate
     *
     * @throws ApiException
     */
    public function getMandate($mandateId, array $parameters = [])
    {
        return $this->connector->mandates->getFor($this, $mandateId, $parameters);
    }

    /**
     * @param  string  $mandateId
     * @return null
     *
     * @throws ApiException
     */
    public function revokeMandate($mandateId)
    {
        return $this->connector->mandates->revokeFor($this, $mandateId, $this->getPresetOptions());
    }

    /**
     * Get all mandates for this customer
     *
     * @return MandateCollection
     *
     * @throws ApiException
     */
    public function mandates()
    {
        return $this->connector->mandates->listFor($this, null, null, $this->getPresetOptions());
    }

    /**
     * Helper function to check for mandate with status valid
     *
     * @return bool
     */
    public function hasValidMandate()
    {
        return $this->mandates()
            ->contains(fn (Mandate $mandate) => $mandate->isValid());
    }

    /**
     * Helper function to check for specific payment method mandate with status valid
     */
    public function hasValidMandateForMethod($method): bool
    {
        return $this->mandates()
            ->contains(fn (Mandate $mandate) => $mandate->isValid() && $mandate->method === $method);
    }
}
