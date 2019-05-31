<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\ApiClient;

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Resources\Event;
use CoinbaseCommerce\Util;
use CoinbaseCommerce\Webhook;

class CoinbaseApiClient implements CoinbaseApiClientInterface
{
    /** @var ApiClient */
    private $apiClient;

    public function initialise(string $apiKey): void
    {
        $this->apiClient = ApiClient::init($apiKey);
    }

    public function create(array $data): Charge
    {
        return Charge::create($data);
    }

    public function show(string $paymentId): Charge
    {
        $response = $this->apiClient->get(sprintf('/charges/%s', $paymentId));

        return Util::convertToApiObject($response);
    }

    public function buildEvent(string $payload, string $signatureHeader, string $secret): Event
    {
        return Webhook::buildEvent($payload, $signatureHeader, $secret);
    }
}
