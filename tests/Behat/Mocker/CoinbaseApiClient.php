<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
