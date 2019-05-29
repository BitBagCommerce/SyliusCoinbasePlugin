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

use CoinbaseCommerce\Resources\Charge;

interface CoinbaseApiClientInterface
{
    public const STATUS_CREATED = 'created';

    public function initialise(string $apiKey): void;

    public function create(array $data): Charge;
}
