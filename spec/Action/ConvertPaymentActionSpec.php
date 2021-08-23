<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusCoinbasePlugin\Action;

use BitBag\SyliusCoinbasePlugin\Action\ConvertPaymentAction;
use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Convert;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function let(PaymentDescriptionProviderInterface $paymentDescriptionProvider): void
    {
        $this->beConstructedWith($paymentDescriptionProvider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_executes(
        Convert $request,
        PaymentInterface $payment,
        GatewayInterface $gateway,
        OrderInterface $order,
        CustomerInterface $customer,
        PaymentDescriptionProviderInterface $paymentDescriptionProvider
    ): void {
        $this->setGateway($gateway);

        $customer->getId()->willReturn(1);
        $order->getCustomer()->willReturn($customer);
        $payment->getOrder()->willReturn($order);
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');
        $payment->getDetails()->willReturn([]);
        $payment->getAmount()->willReturn(1000);
        $payment->getCurrencyCode()->willReturn('EUR');
        $payment->getId()->willReturn(7);
        $paymentDescriptionProvider->getPaymentDescription($payment)->willReturn('test description');

        $request->setResult([
            'name' => 'Payment',
            'description' => 'test description',
            'pricing_type' => CoinbaseApiClientInterface::FIXED_PRICE_PRICING_TYPE,
            'local_price' => [
                'amount' => 10,
                'currency' => 'EUR',
            ],
            'metadata' => [
                'customer_id' => 1,
                'payment_id' => 7,
            ],
        ])->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_convert_request_payment_source_and_array_to(
        Convert $request,
        PaymentInterface $payment
    ): void {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');
        $this->supports($request)->shouldReturn(true);
    }
}
