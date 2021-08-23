<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class CoinbaseGatewayFactory extends GatewayFactory
{
    public const FACTORY_NAME = 'coinbase';

    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'coinbase',
            'payum.http_client' => '@bitbag_sylius_coinbase_plugin.api_client.coinbase',
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'apiKey' => null,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'apiKey',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var CoinbaseApiClientInterface $coinbaseApiClient */
                $coinbaseApiClient = $config['payum.http_client'];

                $coinbaseApiClient->initialise($config['apiKey']);

                return $coinbaseApiClient;
            };
        }
    }
}
