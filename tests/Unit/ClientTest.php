<?php

namespace Tests\Unit;

use GuzzleHttp\Psr7\Request;
use MHD\Emarsys\Api\Authentication;
use MHD\Emarsys\Api\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class ClientTest extends TestCase
{
    public function testAuthenticateRequest()
    {
        $authentication = $this->createMock(Authentication::class);
        $httpClient = $this->createMock(ClientInterface::class);

        $client = new Client($authentication, $httpClient);

        $authentication->method('getNoonce')->willReturn('12345');
        $authentication->method('getAuthenticationHeader')->willReturn('foobar');

        $request = new Request('GET', 'example.org');
        $request = $client->authenticateRequest($request);

        $header = $request->getHeader(Authentication::HEADER_NAME);
        $this->assertEquals($header, ['foobar']);
    }
}
