<?php

declare(strict_types=1);

namespace Mollie\Api\Builders;

use Mollie\Api\MollieApiClient;
use Mollie\Api\Types\SequenceType;

class CreatePaymentBuilder
{
    /**
     * @var \Mollie\Api\MollieApiClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var array
     */
    protected $filters = [];

    public function __construct(MollieApiClient $client)
    {
        $this->client = $client;
    }

    public function description(string $description)
    {
        $this->payload['description'] = $description;

        return $this;
    }

    public function amount(string $value, string $currency)
    {
        $this->payload['amount'] = [
            'value' => $value,
            'currency' => $currency,
        ];

        return $this;
    }

    public function redirectUrl(string $redirectUrl)
    {
        $this->payload['redirectUrl'] = $redirectUrl;

        return $this;
    }

    public function cancelUrl(string $cancelUrl)
    {
        $this->payload['cancelUrl'] = $cancelUrl;

        return $this;
    }

    public function webhookUrl(string $webhookUrl)
    {
        $this->payload['webhookUrl'] = $webhookUrl;

        return $this;
    }

    public function locale(string $locale)
    {
        $this->payload['locale'] = $locale;

        return $this;
    }

    public function method($method)
    {
        $this->payload['method'] = $method;

        return $this;
    }

    public function restrictPaymentMethodsToCountry(string $restrictPaymentMethodsToCountry)
    {
        $this->payload['restrictPaymentMethodsToCountry'] = $restrictPaymentMethodsToCountry;

        return $this;
    }

    public function metadata(array $metadata)
    {
        $this->payload['metadata'] = $metadata;

        return $this;
    }

    public function sequenceType(string $sequenceType)
    {
        $this->payload['sequenceType'] = $sequenceType;

        return $this;
    }

    public function sequenceTypeFirst()
    {
        $this->payload['sequenceType'] = SequenceType::SEQUENCETYPE_FIRST;

        return $this;
    }

    public function sequenceTypeOneOff()
    {
        $this->payload['sequenceType'] = SequenceType::SEQUENCETYPE_ONEOFF;

        return $this;
    }

    public function sequenceTypeRecurring()
    {
        $this->payload['sequenceType'] = SequenceType::SEQUENCETYPE_RECURRING;

        return $this;
    }

    public function customerId(string $customerId)
    {
        $this->payload['customerId'] = $customerId;

        return $this;
    }

    public function applicationFee(string $value, string $currency, string $description)
    {
        $this->payload['applicationFee'] = [
            'amount' => [
                'value' => $value,
                'currency' => $currency,
            ],
            'description' => $description,
        ];

        return $this;
    }

    public function routing($routing)
    {
        $this->payload['routing'] = $routing;

        return $this;
    }

    /**
     * @param string $amountValue A string containing the exact amount of this portion of the payment in the given currency. Make sure to send the right amount of decimals. Non-string values are not accepted.
     * @param string $amountCurrency An ISO 4217 currency code. Currently, only EUR payments can be routed.
     * @param array $destination The type of destination. Currently, only the destination type "organization" is supported.
     * @param string|null $releaseDate Optionally, schedule this portion of the payment to be transferred to its destination on a later date. The date must be given in YYYY-MM-DD format. If no date is given, the funds become available to the balance as soon as the payment succeeds.
     * @return $this
     */
    public function addRoute(string $amountValue, string $amountCurrency, array $destination, string $releaseDate = null)
    {
        if (! array_key_exists("routing", $this->payload)) {
            $this->payload["routing"] = [];
        }

        $newRoute = array_filter([
            "amount" => [
                "value" => $amountValue,
                "currency" => $amountCurrency,
            ],
            "destination" => $destination,
            "releaseDate" => $releaseDate,
        ]);


        $this->payload["routing"][] = $newRoute;

        return $this;
    }

    public function mandateId(string $mandateId)
    {
        $this->payload['mandateId'] = $mandateId;

        return $this;
    }

    public function billingEmail(string $billingEmail)
    {
        $this->payload['billingEmail'] = $billingEmail;

        return $this;
    }

    public function dueDate(string $dueDate)
    {
        $this->payload['dueDate'] = $dueDate;

        return $this;
    }

    public function testmode(bool $testmode)
    {
        $this->payload['testmode'] = $testmode;

        return $this;
    }

    public function testmodeEnabled()
    {
        $this->payload['testmode'] = true;

        return $this;
    }

    public function testmodeDisabled()
    {
        $this->payload['testmode'] = false;

        return $this;
    }

    /**
     *
     * Send the create payment request to the Mollie API.
     * @return \Mollie\Api\Resources\Payment
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function send()
    {
        return $this->client->payments->create($this->payload, $this->filters);
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
