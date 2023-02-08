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

    /**
     * The amount that you want to charge.
     *
     * @param string $value
     * @param string $currency
     * @return $this
     */
    public function amount(string $value, string $currency)
    {
        $this->payload['amount'] = [
            'value' => $value,
            'currency' => $currency,
        ];

        return $this;
    }

    /**
     * The description of the payment you are creating. This will be shown to your customer on their card or bank
     * statement when possible. We truncate the description automatically according to the limits of the used payment
     * method. The description is also visible in any exports you generate.
     *
     * We recommend you use a unique identifier so that you can always link the payment to the order in your back
     * office. This is particularly useful for bookkeeping.
     *
     * The maximum length of the description field differs per payment method, with the absolute maximum being 255
     * characters. The API will not reject strings longer than the maximum length, but it will truncate them to fit.
     *
     * For the KBC/CBC payment method the description will be truncated to 13 characters.
     *
     * @param string $description
     * @return $this
     */
    public function description(string $description)
    {
        $this->payload['description'] = $description;

        return $this;
    }

    /**
     * The URL your customer will be redirected to after the payment process.
     *
     * It could make sense for the redirectUrl to contain a unique identifier – like your order ID – so you can show the
     * right page referencing the order when your customer returns.
     *
     * The parameter can be omitted for recurring payments (sequenceType: recurring) and for Apple Pay payments with an
     * applePayPaymentToken.
     *
     * @param string $redirectUrl
     * @return $this
     */
    public function redirectUrl(string $redirectUrl)
    {
        $this->payload['redirectUrl'] = $redirectUrl;

        return $this;
    }

    /**
     * The URL your consumer will be redirected to when the consumer explicitly cancels the payment. If this URL is not
     * provided, the consumer will be redirected to the redirectUrl instead — see above.
     *
     * Mollie will always give you status updates via webhooks, including for the canceled status. This parameter is
     * therefore entirely optional, but can be useful when implementing a dedicated consumer-facing flow to handle
     * payment cancellations.
     *
     * The parameter can be omitted for recurring payments (sequenceType: recurring) and for Apple Pay payments with an
     * applePayPaymentToken.
     *
     * @param string $cancelUrl
     * @return $this
     */
    public function cancelUrl(string $cancelUrl)
    {
        $this->payload['cancelUrl'] = $cancelUrl;

        return $this;
    }

    /**
     * Set the webhook URL, where Mollie will send payment status updates to.
     *
     * The webhookUrl is optional, but without a webhook you will miss out on important status changes to your payment.
     *
     * The webhookUrl must be reachable from Mollie’s point of view, so you cannot use localhost. If you want to use
     * webhook during development on localhost, you must use a tool like ngrok to have the webhooks delivered to your
     * local machine.
     *
     * @param string $webhookUrl
     * @return $this
     */
    public function webhookUrl(string $webhookUrl)
    {
        $this->payload['webhookUrl'] = $webhookUrl;

        return $this;
    }

    /**
     * Allows you to preset the language to be used in the hosted payment pages shown to the consumer. Setting a locale
     * is highly recommended and will greatly improve your conversion rate. When this parameter is omitted, the browser
     * language will be used instead if supported by the payment method. You can provide any "xx_XX" ISO 15897 locale, a
     * subset of languages is supported by the hosted payment pages.
     *
     * For bank transfer payments specifically, the locale will determine the target bank account the customer has to
     * transfer the money to. We have dedicated bank accounts for Belgium, Germany, and The Netherlands. Having the
     * customer use a local bank account greatly increases the conversion and speed of payment.
     *
     * @param string $locale
     * @return $this
     */
    public function locale(string $locale)
    {
        $this->payload['locale'] = $locale;

        return $this;
    }

    /**
     * Normally, a payment method screen is shown. However, when using this parameter, you can choose a specific payment
     * method and your customer will skip the selection screen and is sent directly to the chosen payment method. The
     * parameter enables you to fully integrate the payment method selection into your website.
     *
     * You can also specify the methods in an array. By doing so Mollie will still show the payment method selection
     * screen but will only show the methods specified in the array.
     *
     * @param string|array $method
     * @return $this
     */
    public function method($method)
    {
        $this->payload['method'] = $method;

        return $this;
    }

    /**
     * For digital goods in most jurisdictions, you must apply the VAT rate from your customer’s country. Choose the
     * VAT rates you have used for the order to ensure your customer’s country matches the VAT country. Use this
     * method to restrict the payment methods available to your customer to those from a single country.
     *
     * If available, the credit card method will still be offered, but only cards from the allowed country are accepted.
     *
     * This method expects a country code in ISO 3166-1 alpha-2 format, for example NL.
     *
     * @example NL
     *
     * @param string $restrictPaymentMethodsToCountry
     * @return $this
     */
    public function restrictPaymentMethodsToCountry(string $restrictPaymentMethodsToCountry)
    {
        $this->payload['restrictPaymentMethodsToCountry'] = $restrictPaymentMethodsToCountry;

        return $this;
    }

    /**
     * Provide any data you like, as a string or an array. Mollie will save the data alongside the payment.
     * Whenever you fetch the payment with our API, the metadata will also be included.
     *
     * You can use up to approximately 1kB.
     *
     * @param string|array $metadata
     * @return $this
     */
    public function metadata($metadata)
    {
        $this->payload['metadata'] = $metadata;

        return $this;
    }

    /**
     * Indicate which type of payment this is in a recurring sequence. If set to "first", a first payment is created
     * for the customer, allowing the customer to agree to automatic recurring charges taking place on their account in
     * the future. If set to "recurring", the customer’s card is charged automatically.
     *
     * Defaults to "oneoff", which is a regular non-recurring payment.
     *
     * For PayPal payments, recurring is only possible if PayPal has activated Reference Transactions on your merchant
     * account.
     *
     * @param string $sequenceType
     * @return $this
     */
    public function sequenceType(string $sequenceType)
    {
        $this->payload['sequenceType'] = $sequenceType;

        return $this;
    }

    /**
     * Indicates the payment is the first payment in a recurring sequence, allowing the customer to agree to automatic
     * recurring charges taking place on their account in the future.
     *
     * Ensure to also set the customerId before sending the request.
     *
     * @return $this
     */
    public function sequenceTypeFirst()
    {
        $this->payload['sequenceType'] = SequenceType::SEQUENCETYPE_FIRST;

        return $this;
    }

    /**
     * Indicates the payment is a recurring payment, using a previously obtained mandate for the customer.
     * The customer’s card is charged automatically.
     *
     * Ensure to also set the customerId and optionally a specific MandateId before sending the request.
     *
     * @return $this
     */
    public function sequenceTypeRecurring()
    {
        $this->payload['sequenceType'] = SequenceType::SEQUENCETYPE_RECURRING;

        return $this;
    }

    /**
     * The ID of the customer for whom the payment is being created. This is used primarily for recurring payments, but
     * can also be used on regular payments to enable single-click payments.
     *
     * Either this field or the mandateId field needs to be provided for payments with the recurring sequence type.
     *
     * Storing the customerId on the payment allows you to track the customer and related payments in Mollie's
     * dashboard.
     *
     * @param string $customerId
     * @return $this
     */
    public function customerId(string $customerId)
    {
        $this->payload['customerId'] = $customerId;

        return $this;
    }

    /**
     * When creating recurring payments, the ID of a specific mandate can be supplied to indicate which of the
     * consumer’s accounts should be credited.
     *
     * Either this field or the customerId field needs to be provided for payments with the recurring sequence type.
     *
     * @param string $mandateId
     * @return $this
     */
    public function mandateId(string $mandateId)
    {
        $this->payload['mandateId'] = $mandateId;

        return $this;
    }

    /**
     * The website profile’s unique identifier.
     *
     * @example pfl_3RkSN1zuPE
     *
     * @param string $profileId
     * @return $this
     */
    public function profileId(string $profileId)
    {
        $this->payload['profileId'] = $profileId;

        return $this;
    }

    /**
     * When using Mollie Connect, set this to true to make this payment a test payment.
     *
     * @param bool $testmode
     * @return $this
     */
    public function testmode(bool $testmode)
    {
        $this->payload['testmode'] = $testmode;

        return $this;
    }

    /**
     * When using Mollie Connect, use this to set testmode to true and make this payment a test payment.
     *
     * @return $this
     */
    public function testmodeEnabled()
    {
        $this->payload['testmode'] = true;

        return $this;
    }

    /**
     * By default, testmode is disabled. When using Mollie Connect, use this to set testmode to true and make this
     * payment a test payment.
     *
     * @return $this
     */
    public function testmodeDisabled()
    {
        $this->payload['testmode'] = false;

        return $this;
    }

    /**
     * Adding an application fee allows you to charge the merchant a small sum for the payment and transfer this to
     * your own account.
     *
     * @param string $value
     * @param string $currency
     * @param string $description
     * @return $this
     */
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

    /**
     * An optional routing configuration which enables you to route a successful payment, or part of the payment, to
     * one or more connected accounts. Additionally, you can schedule (parts of) the payment to become available on the
     * connected account on a future date.
     *
     * @param array $routing
     * @return $this
     */
    public function routing(array $routing)
    {
        $this->payload['routing'] = $routing;

        return $this;
    }

    /**
     * Add a route to the routing configuration.
     *
     * The optional routing configuration which enables you to route a successful payment, or part of the payment, to
     * one or more connected accounts. Additionally, you can schedule (parts of) the payment to become available on the
     * connected account on a future date.
     *
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

    /**
     * Payment-method specific parameter for Apple Pay. The Apple Pay Payment Token object (encoded as JSON) that is part of the
     * result of authorizing a payment request. The token contains the payment information needed to authorize the
     * payment.
     *
     * The token object should be passed encoded in a JSON string.
     *
     * If you specify the method parameter, optional parameters may be available for the payment method. If no method
     * is specified, you can still send the optional parameters and Mollie will apply them when the consumer selects
     * the relevant payment method.
     *
     * @example {"paymentData": {"version": "EC_v1", "data": "vK3BbrCbI/...."}}
     * @param string $encodedApplePayPaymentToken
     * @return $this
     */
    public function applePayPaymentTokenFromEncodedJSONString(string $encodedApplePayPaymentToken)
    {
        $this->payload['applePayPaymentToken'] = $encodedApplePayPaymentToken;

        return $this;
    }
    
    /**
     * Payment-method specific parameter for bank transfers and Przelewy24. Set the consumer’s email address, to automatically send
     * the bank transfer details to.
     *
     * Note: the payment instructions will be sent immediately when creating the payment. If you do not specify the
     * locale parameter, the email will be sent in English, as we haven’t yet been able to detect the consumer’s
     * browser language.
     *
     * @param string $billingEmail
     * @return $this
     */
    public function billingEmail(string $billingEmail)
    {
        $this->payload['billingEmail'] = $billingEmail;

        return $this;
    }

    /**
     * Payment-method specific parameter for bank transfers. Set the date the payment should expire,
     * in YYYY-MM-DD format.
     *
     * Note: the minimum date is tomorrow and the maximum date is 100 days after tomorrow.
     *
     * After you created the payment, you can still update the dueDate via Update payment.
     *
     * @param string $dueDate
     * @return $this
     */
    public function dueDate(string $dueDate)
    {
        $this->payload['dueDate'] = $dueDate;

        return $this;
    }

    /**
     * Payment-method specific parameter for credit card. Set the cardholder’s address details.
     *
     * It is advised to provide these details to improve the credit card fraud protection, and thus improve conversion.
     *
     * If an address is provided, then the address has to be in a valid format. See the address object documentation
     * for more information on which formats are accepted.
     *
     * @link https://docs.mollie.com/overview/common-data-types#address-object
     *
     * @param array $billingAddress
     * @return $this
     */
    public function billingAddress(array $billingAddress)
    {
        $this->payload['billingAddress'] = $billingAddress;

        return $this;
    }

    /**
     * Payment-method specific parameter for credit card. Set the card token retrieved using Mollie Components.
     *
     * The token contains the card information (such as cardholder, card number, and expiry date) needed to complete
     * the payment.
     *
     * @param string $cardToken
     * @return $this
     */
    public function cardToken(string $cardToken)
    {
        $this->payload['cardToken'] = $cardToken;

        return $this;
    }

    /**
     * Payment-method specific parameter for credit card and PayPal. Set the shipping address details.
     *
     * It is advised to provide these details to improve the credit card fraud protection, and thus improve conversion.
     * If an address is provided, then the address has to be in a valid format.
     * See the address object documentation for more information on which formats are accepted.
     *
     * @link https://docs.mollie.com/overview/common-data-types#address-object
     * @param array $shippingAddress
     * @return $this
     */
    public function shippingAddress(array $shippingAddress)
    {
        $this->payload['shippingAddress'] = $shippingAddress;

        return $this;
    }

    /**
     * Payment-method specific parameter for iDEAL, KBC/CBC and gift cards. This is useful when you want to embed the
     * method specific issuer selection on your own checkout screen.
     *
     * For any of these methods, the full list of issuers can be retrieved via the Methods API using the
     * "issuers" include.
     *
     * iDEAL: An iDEAL issuer ID, for example ideal_INGBNL2A. When supplying an issuer ID, the returned payment URL will deep-link to the specific
     * banking website (ING Bank, in this example).
     *
     * @param string $issuer
     * @return $this
     */
    public function issuer(string $issuer)
    {
        $this->payload['issuer'] = $issuer;

        return $this;
    }

    /**
     * Payment-method specific parameter for gift cards. Set the gift number to prefill the card number in the hosted
     * payment page.
     *
     * @param string $voucherNumber
     * @return $this
     */
    public function voucherNumber(string $voucherNumber)
    {
        $this->payload['voucherNumber'] = $voucherNumber;

        return $this;
    }

    /**
     * Payment-method specific parameter for gift cards. Set the PIN code from the gift card.
     *
     * Supply this to prefill the gift card PIN in the hosted payment page. Note: not all gift cards have a PIN.
     *
     * @param string $voucherPin
     * @return $this
     */
    public function voucherPin(string $voucherPin)
    {
        $this->payload['voucherPin'] = $voucherPin;

        return $this;
    }

    /**
     * Generate a QR code. QR codes can be generated for iDEAL, Bancontact and bank transfer payments.
     *
     * @return $this
     */
    public function generateQRCode()
    {
        return $this->addFilterInclude('details.qrCode');
    }

    /**
     * Manually add a set of payload items to the builder generated payload.
     *
     * @param array $payload
     * @return $this
     */
    public function addPayload(array $payload)
    {
        $this->payload = array_merge($this->payload, $payload);

        return $this;
    }

    /**
     * Manually add filter items to the builder generated filters.
     *
     * @param array $filters
     * @return $this
     */
    public function addFilters(array $filters)
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    /**
     * Add a specific include statement to the filters.
     *
     * @param string $include
     * @return $this
     */
    public function addFilterInclude(string $include)
    {
        if (! array_key_exists("include", $this->filters)) {
            $this->filters["include"] = $include;
        } else {
            $this->filters['include'] .= ','.$include;
        }

        return $this;
    }

    /**
     * Send the create payment request to the Mollie API.
     * @return \Mollie\Api\Resources\Payment
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function send()
    {
        return $this->client->payments->create($this->payload, $this->filters);
    }

    /**
     * Retrieve this builder's generated payload ready to be sent to the Mollie API.
     *
     * This is helpful for inspection and testing.
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Retrieve this builder's filters generated filters ready to be sent to the Mollie API.
     *
     * This is helpful for inspection and testing.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
