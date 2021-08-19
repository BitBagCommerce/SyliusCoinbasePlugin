<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCoinbasePlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Tests\BitBag\SyliusCoinbasePlugin\Behat\Mocker\CoinbaseApiMocker;
use Tests\BitBag\SyliusCoinbasePlugin\Behat\Page\Shop\Payum\PaymentPageInterface;

final class CheckoutContext implements Context
{
    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    /** @var CoinbaseApiMocker */
    private $coinbaseApiMocker;

    /** @var PaymentPageInterface */
    private $paymentPage;

    /** @var PaymentRepositoryInterface */
    private $paymentRepository;

    public function __construct(
        CompletePageInterface $summaryPage,
        ShowPageInterface $orderDetails,
        CoinbaseApiMocker $coinbaseApiMocker,
        PaymentPageInterface $paymentPage,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->summaryPage = $summaryPage;
        $this->orderDetails = $orderDetails;
        $this->coinbaseApiMocker = $coinbaseApiMocker;
        $this->paymentPage = $paymentPage;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @When I confirm my order with Coinbase payment
     * @Given I have confirmed my order with Coinbase payment
     */
    public function iConfirmMyOrderWithCoinbasePayment(): void
    {
        $this->coinbaseApiMocker->mockApiCreatePayment(function () {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @When I sign in to Coinbase and pay successfully
     */
    public function iSignInToCoinbaseAndPaySuccessfully(): void
    {
        $payments = $this->paymentRepository->findAll();

        $this->coinbaseApiMocker->mockApiSuccessfulPayment(function () use ($payments) {
            $this->paymentPage->notify([
                'event' => [
                    'data' => [
                        'id' => 'test',
                        'metadata' => [
                            'payment_id' => end($payments)->getId(),
                        ],
                    ],
                ],
            ]);

            $this->paymentPage->capture();
        });
    }

    /**
     * @Given I have failed Coinbase payment
     */
    public function iHaveFailedCoinbasePayment()
    {
        $payments = $this->paymentRepository->findAll();

        $this->coinbaseApiMocker->mockApiFailedPayment(function () use ($payments) {
            $this->paymentPage->notify([
                'event' => [
                    'data' => [
                        'id' => 'test',
                        'metadata' => [
                            'payment_id' => end($payments)->getId(),
                        ],
                    ],
                ],
            ]);

            $this->paymentPage->capture();
        });
    }

    /**
     * @When I cancel my Coinbase payment
     * @Given I have cancelled Coinbase payment
     */
    public function iCancelMyCoinbasePayment(): void
    {
        $payments = $this->paymentRepository->findAll();

        $this->coinbaseApiMocker->mockApiCancelledPayment(function () use ($payments) {
            $this->paymentPage->notify([
                'event' => [
                    'data' => [
                        'id' => 'test',
                        'metadata' => [
                            'payment_id' => end($payments)->getId(),
                        ],
                    ],
                ],
            ]);

            $this->paymentPage->capture();
        });
    }

    /**
     * @When I try to pay again Coinbase payment
     */
    public function iTryToPayAgainCoinbasePayment(): void
    {
        $this->coinbaseApiMocker->mockApiCreatePayment(function () {
            $this->orderDetails->pay();
        });
    }
}
