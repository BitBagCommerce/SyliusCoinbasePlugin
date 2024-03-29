<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Action;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class StatusAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        $details = $payment->getDetails();

        if (!isset($details['status']) || !isset($details['payment_id'])) {
            $request->markNew();

            return;
        }

        switch ($details['status']) {
            case CoinbaseApiClientInterface::STATUS_CREATED:
            case CoinbaseApiClientInterface::STATUS_NEW:
            case CoinbaseApiClientInterface::STATUS_PENDING:
                $request->markPending();

                break;
            case CoinbaseApiClientInterface::STATUS_CANCELED:
                $request->markCanceled();

                break;
            case CoinbaseApiClientInterface::STATUS_COMPLETED:
                $request->markCaptured();

                break;
            default:
                $request->markFailed();

                break;
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
