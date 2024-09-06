<?php

namespace Mollie\Api\Http\Payload;

class CreateCustomerPayload extends DataBag
{
    public ?string $name;

    public ?string $email;

    public ?string $locale;

    public ?Metadata $metadata;

    public ?bool $testmode;

    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $locale = null,
        ?Metadata $metadata = null,
        ?bool $testmode = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->locale = $locale;
        $this->metadata = $metadata;
        $this->testmode = $testmode;
    }

    public function data()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
            'metadata' => $this->metadata,
            'testmode' => $this->testmode,
        ];
    }
}
