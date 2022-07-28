<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

    public function buildEvent(
        string $payload,
        string $signatureHeader,
        string $secret
    ): Event {
        return Webhook::buildEvent($payload, $signatureHeader, $secret);
    }
}
