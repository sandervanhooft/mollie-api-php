<?php

declare(strict_types=1);

namespace Mollie\examples;

use Mollie\Api\MollieApiClient;

abstract class BaseExample
{
    /**
     * @var \Mollie\Api\MollieApiClient
     */
    protected $mollie;

    /**
     * @param \Mollie\Api\MollieApiClient $mollie
     */
    public function __construct(MollieApiClient $mollie)
    {
        $this->mollie = $mollie;
    }

    /**
     * Determine the url parts to these example files.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
        $hostname = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF']);

        return "{$protocol}://{$hostname}{$path}";
    }

    /**
     * Redirect the customer to the provided url.
     *
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: ".$url, true, 303);
    }
}