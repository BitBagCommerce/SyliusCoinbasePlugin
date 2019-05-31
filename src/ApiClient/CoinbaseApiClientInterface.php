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
use CoinbaseCommerce\Resources\Event;

interface CoinbaseApiClientInterface
{
    public const WEBHOOK_SIGNATURE_HEADER_NAME = 'x-cc-webhook-signature';
    public const STATUS_CREATED = 'created';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_NEW = 'new';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function initialise(string $apiKey): void;

    public function create(array $data): Charge;

    public function show(string $paymentId): Charge;

    public function buildEvent(string $payload, string $signatureHeader, string $secret): Event;
}
