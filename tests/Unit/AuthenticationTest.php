<?php

namespace Tests\Unit;

use DateTime;
use MHD\Emarsys\Api\Authentication;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @var Authentication
     */
    private $authentication;

    public function setUp(): void
    {
        $this->authentication = new Authentication('username', 'secret');
    }

    public function testNoonceIsCorrectLength()
    {
        $this->assertMatchesRegularExpression(
            '/[0-9a-f]{32}/',
            $this->authentication->getNoonce()
        );
    }

    public function testNoonceIsLikelyUnique()
    {
        $this->assertNotEquals(
            $this->authentication->getNoonce(),
            $this->authentication->getNoonce()
        );
    }

    /**
     * @dataProvider formatHeaderDataProvider
     */
    public function testFormatHeader(
        string $expected,
        string $passwordDigest,
        string $noonce,
        DateTime $timestamp
    ) {
        $this->assertEquals(
            $expected,
            $this->authentication->formatHeader($passwordDigest, $noonce, $timestamp)
        );
    }

    public function formatHeaderDataProvider()
    {
        yield [
            'UsernameToken Username="username",PasswordDigest="",Nonce="",Created="1970-01-01T00:00:00+0000"',
            '',
            '',
            new DateTime('@0')
        ];

        yield [
            'UsernameToken Username="username",PasswordDigest="foo",Nonce="bar",Created="2009-02-13T23:31:30+0000"',
            'foo',
            'bar',
            new DateTime('@1234567890')
        ];
    }

    public function testGetPasswordDigest()
    {
        $this->assertEquals(
            'NjgwYWJkYjNkODliYjc4OTcxZDgyNjFlOTUzZWVkYjNiOTY3YWY4MQ==',
            $this->authentication->getPasswordDigest(
                '12345678901234567890123456789012',
                new DateTime('@1234567890')
            )
        );
    }

    public function testGetAuthenticationHeader()
    {
        $this->assertEquals(
            'UsernameToken Username="username",' .
            'PasswordDigest="NjgwYWJkYjNkODliYjc4OTcxZDgyNjFlOTUzZWVkYjNiOTY3YWY4MQ==",' .
            'Nonce="12345678901234567890123456789012",' .
            'Created="2009-02-13T23:31:30+0000"',
            $this->authentication->getAuthenticationHeader(
                '12345678901234567890123456789012',
                new DateTime('@1234567890')
            )
        );
    }
}
