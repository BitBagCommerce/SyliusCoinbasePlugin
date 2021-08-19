<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCoinbasePlugin\Behat\Page\Shop\Payum;

use Behat\Mink\Session;
use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

final class PaymentPage extends Page implements PaymentPageInterface
{
    /** @var RepositoryInterface */
    private $securityTokenRepository;

    /** @var AbstractBrowser */
    private $client;

    /** @var RouterInterface */
    private $router;

    public function __construct(
        Session $session,
        $parameters,
        RepositoryInterface $securityTokenRepository,
        AbstractBrowser $client,
        RouterInterface $router
    ) {
        parent::__construct($session, $parameters);

        $this->securityTokenRepository = $securityTokenRepository;
        $this->client = $client;
        $this->router = $router;
    }

    public function capture(array $parameters = []): void
    {
        $captureToken = $this->findToken();

        $url = $captureToken->getTargetUrl();

        if (count($parameters) > 0) {
            $url .= '?' . http_build_query($parameters);
        }

        $this->getDriver()->visit($url);
    }

    public function notify(array $postData): void
    {
        $this->client->request(
            'POST',
            $this->router->generate('bitbag_sylius_coinbase_plugin_payment_notify'),
            [],
            [],
            ['HTTP_' . CoinbaseApiClientInterface::WEBHOOK_SIGNATURE_HEADER_NAME => 'hash'],
            json_encode($postData)
        );

        /** @var Response $response */
        $response = $this->client->getResponse();

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \RuntimeException();
        }
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return '';
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $tokens = [];

        /** @var TokenInterface $token */
        foreach ($this->securityTokenRepository->findAll() as $token) {
            if (strpos($token->getTargetUrl(), $type)) {
                $tokens[] = $token;
            }
        }

        if (count($tokens) > 0) {
            return end($tokens);
        }

        throw new \RuntimeException('Cannot find capture token, check if you are after proper checkout steps');
    }
}
