<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\ApiClient;

use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Resources\Event;

interface CoinbaseApiClientInterface
{
    public const WEBHOOK_SIGNATURE_HEADER_NAME = 'x-cc-webhook-signature';

    public const FIXED_PRICE_PRICING_TYPE = 'fixed_price';

    public const STATUS_CREATED = 'created';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_NEW = 'new';

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public function initialise(string $apiKey): void;

    public function create(array $data): Charge;

    public function show(string $paymentId): Charge;

    public function buildEvent(
        string $payload,
        string $signatureHeader,
        string $secret
    ): Event;
}
