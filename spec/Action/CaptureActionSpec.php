<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusCoinbasePlugin\Action;

use BitBag\SyliusCoinbasePlugin\Action\CaptureAction;
use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use CoinbaseCommerce\Resources\Charge;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactory;
use Payum\Core\Security\TokenInterface;
use PhpSpec\ObjectBehavior;

final class CaptureActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CaptureAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldHaveType(GatewayAwareInterface::class);
    }

    function it_executes(
        Capture $request,
        ArrayObject $arrayObject,
        TokenInterface $token,
        GatewayInterface $gateway,
        CoinbaseApiClientInterface $coinbaseApiClient,
        GenericTokenFactory $genericTokenFactory,
        TokenInterface $notifyToken
    ): void {
        $charge = \Mockery::mock('charge', Charge::class);

        $charge->id = 1;
        $charge->hosted_url = 'test';

        $this->setGateway($gateway);
        $this->setApi($coinbaseApiClient);
        $this->setGenericTokenFactory($genericTokenFactory);

        $arrayObject->getArrayCopy()->willReturn([]);
        $request->getModel()->willReturn($arrayObject);
        $request->getToken()->willReturn($token);
        $token->getTargetUrl()->willReturn('url');
        $token->getGatewayName()->willReturn('test');
        $token->getDetails()->willReturn([]);
        $coinbaseApiClient->create([])->willReturn($charge);

        $arrayObject->offsetExists('status')->shouldBeCalled();
        $arrayObject->offsetSet('redirect_url', 'url')->shouldBeCalled();
        $arrayObject->offsetSet('cancel_url', 'url')->shouldBeCalled();
        $arrayObject->offsetSet('payment_id', 1)->shouldBeCalled();
        $arrayObject->offsetSet('status', CoinbaseApiClientInterface::STATUS_CREATED)->shouldBeCalled();

        $this
            ->shouldThrow(HttpRedirect::class)
            ->during('execute', [$request])
        ;
    }

    function it_supports_only_capture_request_and_array_access(
        Capture $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);
        $this->supports($request)->shouldReturn(true);
    }
}
