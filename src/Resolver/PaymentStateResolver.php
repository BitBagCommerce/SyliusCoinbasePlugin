<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Resolver;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use BitBag\SyliusCoinbasePlugin\CoinbaseGatewayFactory;
use CoinbaseCommerce\Resources\Charge;
use Doctrine\ORM\EntityManagerInterface;
use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\PaymentTransitions;

final class PaymentStateResolver implements PaymentStateResolverInterface
{
    /** @var FactoryInterface */
    private $stateMachineFactory;

    /** @var CoinbaseApiClientInterface */
    private $coinbaseApiClient;

    /** @var EntityManagerInterface */
    private $paymentEntityManager;

    public function __construct(
        FactoryInterface $stateMachineFactory,
        CoinbaseApiClientInterface $coinbaseApiClient,
        EntityManagerInterface $paymentEntityManager
    ) {
        $this->stateMachineFactory = $stateMachineFactory;
        $this->coinbaseApiClient = $coinbaseApiClient;
        $this->paymentEntityManager = $paymentEntityManager;
    }

    public function resolve(PaymentInterface $payment): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (CoinbaseGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()) {
            return;
        }

        $details = $payment->getDetails();

        if (!isset($details['payment_id'])) {
            return;
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        $this->coinbaseApiClient->initialise($gatewayConfig['apiKey']);

        $charge = $this->coinbaseApiClient->show($details['payment_id']);

        $this->resolveState($payment, $charge);
    }

    private function resolveState(PaymentInterface $payment, Charge $charge): void
    {
        $paymentStateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        $timelineLast = end($charge->timeline);

        switch (strtolower($timelineLast['status'])) {
            case CoinbaseApiClientInterface::STATUS_CANCELED:
                $this->applyTransition($paymentStateMachine, PaymentTransitions::TRANSITION_CANCEL);
                break;
            case CoinbaseApiClientInterface::STATUS_COMPLETED:
                $this->applyTransition($paymentStateMachine, PaymentTransitions::TRANSITION_COMPLETE);
                break;
            case CoinbaseApiClientInterface::STATUS_PENDING:
            case CoinbaseApiClientInterface::STATUS_CREATED:
            case CoinbaseApiClientInterface::STATUS_NEW:
                $this->applyTransition($paymentStateMachine, PaymentTransitions::TRANSITION_PROCESS);
                break;
            default:
                $this->applyTransition($paymentStateMachine, PaymentTransitions::TRANSITION_FAIL);
        }

        $this->paymentEntityManager->flush();
    }

    private function applyTransition(StateMachineInterface $paymentStateMachine, string $transition): void
    {
        if ($paymentStateMachine->can($transition)) {
            $paymentStateMachine->apply($transition);
        }
    }
}
