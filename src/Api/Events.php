<?php

namespace MHD\Emarsys\Api;

use GuzzleHttp\Psr7\Request;
use MHD\Emarsys\Data\ContactFields;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * @link https://dev.emarsys.com/v2/events
 */
class Events
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @link https://dev.emarsys.com/v2/events/list-external-events
     */
    public function getList(): ResponseInterface
    {
        $request = new Request('GET', '/event/');

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/events/query-external-event
     */
    public function getEvent(int $eventId): ResponseInterface
    {
        $request = new Request('GET', "/event/{$eventId}");

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/events/trigger-external-events
     */
    public function triggerEvent(
        int    $eventId,
        string $externalId,
        array  $data = [],
        int    $keyId = ContactFields::ID_EMAIL
    ): ResponseInterface {
        $payload = [
            'key_id' => $keyId,
            'external_id' => $externalId,
            'data' => $data ?: new stdClass(),
        ];
        $request = new Request(
            'POST',
            "/event/{$eventId}/trigger",
            [],
            json_encode($payload)
        );

        return $this->client->sendRequest($request);
    }
}
