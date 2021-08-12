<?php

namespace MHD\Emarsys\Api;

use DateTime;

/**
 * @link https://dev.emarsys.com/v2/before-you-start/authentication
 */
class Authentication
{
    public const HEADER_NAME = 'X-WSSE';
    public const HEADER_FORMAT = 'UsernameToken Username="%s",PasswordDigest="%s",Nonce="%s",Created="%s"';

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $username, string $secret)
    {
        $this->username = $username;
        $this->secret = $secret;
    }

    public function getNoonce(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function getAuthenticationHeader(string $noonce, DateTime $timestamp): string
    {
        $passwordDigest = $this->getPasswordDigest($noonce, $timestamp);

        return $this->formatHeader($passwordDigest, $noonce, $timestamp);
    }

    public function getPasswordDigest(string $nonce, DateTime $timestamp): string
    {
        return base64_encode(sha1($nonce . $timestamp->format(DATE_ISO8601) . $this->secret));
    }

    public function formatHeader(
        string $passwordDigest,
        string $noonce,
        DateTime $timestamp
    ): string {
        return sprintf(
            self::HEADER_FORMAT,
            $this->username,
            $passwordDigest,
            $noonce,
            $timestamp->format(DATE_ISO8601)
        );
    }
}
