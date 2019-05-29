<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Action\Api;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use Payum\Core\Exception\UnsupportedApiException;

trait ApiAwareTrait
{
    /** @var CoinbaseApiClientInterface */
    protected $coinbaseApiClient;

    public function setApi($coinbaseApiClient): void
    {
        if (!$coinbaseApiClient instanceof CoinbaseApiClientInterface) {
            throw new UnsupportedApiException('Not supported.Expected an instance of ' . CoinbaseApiClientInterface::class);
        }

        $this->coinbaseApiClient = $coinbaseApiClient;
    }
}
