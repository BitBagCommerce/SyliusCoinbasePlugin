<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Controller\Action\Payment;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use BitBag\SyliusCoinbasePlugin\CoinbaseGatewayFactory;
use BitBag\SyliusCoinbasePlugin\Resolver\PaymentStateResolverInterface;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotifyAction
{
    /** @var PaymentRepositoryInterface */
    private $paymentRepository;

    /** @var FactoryInterface */
    private $stateMachineFactory;

    /** @var CoinbaseApiClientInterface */
    private $coinbaseApiClient;

    /** @var PaymentStateResolverInterface */
    private $paymentStateResolver;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        FactoryInterface $stateMachineFactory,
        CoinbaseApiClientInterface $coinbaseApiClient,
        PaymentStateResolverInterface $paymentStateResolver
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->coinbaseApiClient = $coinbaseApiClient;
        $this->paymentStateResolver = $paymentStateResolver;
    }

    public function __invoke(Request $request): Response
    {
        $headers = $request->headers;

        if (!$headers->has(CoinbaseApiClientInterface::WEBHOOK_SIGNATURE_HEADER_NAME)) {
            throw new NotFoundHttpException();
        }

        $signatureHeader = $headers->get(CoinbaseApiClientInterface::WEBHOOK_SIGNATURE_HEADER_NAME);

        $payload = json_decode($request->getContent(), true);

        if (!isset($payload['event']['data']['metadata']['payment_id'])) {
            throw new BadRequestHttpException();
        }

        $paymentId = (int) $payload['event']['data']['metadata']['payment_id'];

        /** @var PaymentInterface $payment */
        $payment = $this->paymentRepository->find($paymentId);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (CoinbaseGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getConfig()['factoryName']) {
            throw new BadRequestHttpException();
        }

        $details = $payment->getDetails();

        if (!(isset($details['payment_id']) && $details['payment_id'] === $payload['event']['data']['id'])) {
            throw new BadRequestHttpException();
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        try {
            $event = $this->coinbaseApiClient->buildEvent($request->getContent(), $signatureHeader, $gatewayConfig['webhookSecretKey']);
        } catch (\Exception $exception) {
            throw new BadRequestHttpException();
        }

        if ('charge:created' === $event->type) {
            return new Response('', Response::HTTP_OK);
        }

        $this->paymentStateResolver->resolve($payment);

        return new Response('', Response::HTTP_OK);
    }
}
