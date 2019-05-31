<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusCoinbasePlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setApiKey(string $apiKey): void;

    public function setWebhookSecretKey(string $webhookSecretKey): void;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
