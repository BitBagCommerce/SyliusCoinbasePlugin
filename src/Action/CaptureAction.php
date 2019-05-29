<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Action;

use BitBag\SyliusCoinbasePlugin\Action\Api\ApiAwareTrait;
use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    /** @var GenericTokenFactoryInterface|null */
    private $tokenFactory;

    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null): void
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (isset($details['status']) && isset($details['payment_id'])) {
            return;
        }

        /** @var TokenInterface $token */
        $token = $request->getToken();

        $details['redirect_url'] = str_replace('http://127.0.0.1:8005', 'http://915baac2.ngrok.io', $token->getTargetUrl());
        $details['cancel_url'] = str_replace('http://127.0.0.1:8005', 'http://915baac2.ngrok.io', $token->getTargetUrl());

        $charge = $this->coinbaseApiClient->create($details->getArrayCopy());

        $details['payment_id'] = $charge->code;
        $details['status'] = CoinbaseApiClientInterface::STATUS_CREATED;

        throw new HttpRedirect($charge->hosted_url);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}