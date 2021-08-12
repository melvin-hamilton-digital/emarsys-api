<?php

namespace MHD\Emarsys\Api;

use DateTime;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(
        Authentication  $authentication,
        ClientInterface $httpClient
    ) {
        $this->authentication = $authentication;
        $this->httpClient = $httpClient;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $this->authenticateRequest($request);

        return $this->httpClient->sendRequest($request);
    }

    public function authenticateRequest(RequestInterface $request): RequestInterface
    {
        $noonce = $this->authentication->getNoonce();
        $header = $this->authentication->getAuthenticationHeader($noonce, new DateTime());

        return $request->withAddedHeader(Authentication::HEADER_NAME, $header);
    }
}
