<?php

namespace Mollie\Api\Http\Payload;

use Mollie\Api\Contracts\DataProvider;
use Mollie\Api\Traits\ComposableFromArray;

class Owner implements DataProvider
{
    use ComposableFromArray;

    public string $email;

    public string $givenName;

    public string $familyName;

    public ?string $locale = null;

    public function __construct(
        string $email,
        string $givenName,
        string $familyName,
        ?string $locale = null
    ) {
        $this->email = $email;
        $this->givenName = $givenName;
        $this->familyName = $familyName;
        $this->locale = $locale;
    }

    public function data(): array
    {
        return [
            'email' => $this->email,
            'givenName' => $this->givenName,
            'familyName' => $this->familyName,
            'locale' => $this->locale,
        ];
    }
}
