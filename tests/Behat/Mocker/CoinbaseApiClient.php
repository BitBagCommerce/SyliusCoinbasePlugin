<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCoinbasePlugin\Behat\Mocker;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Resources\Event;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoinbaseApiClient implements CoinbaseApiClientInterface
{
    /** @var CoinbaseApiClientInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initialise(string $apiKey): void
    {
        $this->container->get('bitbag_sylius_coinbase_plugin.api_client.coinbase')->initialise($apiKey);
    }

    public function create(array $data): Charge
    {
        return $this->container->get('bitbag_sylius_coinbase_plugin.api_client.coinbase')->create($data);
    }

    public function show(string $paymentId): Charge
    {
        return $this->container->get('bitbag_sylius_coinbase_plugin.api_client.coinbase')->show($paymentId);
    }

    public function buildEvent(string $payload, string $signatureHeader, string $secret): Event
    {
        return $this->container->get('bitbag_sylius_coinbase_plugin.api_client.coinbase')->buildEvent($payload, $signatureHeader, $secret);
    }
}
