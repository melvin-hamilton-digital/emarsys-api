<?php

namespace MHD\Emarsys\Api;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class Fields
{
    public const TYPE_SHORT_TEXT = 'shorttext'; // max 60 characters
    public const TYPE_LONG_TEXT = 'longtext';   // max 255 characters
    public const TYPE_LARGE_TEXT = 'largetext'; // no limit
    public const TYPE_DATE = 'date';
    public const TYPE_URL = 'url';
    public const TYPE_NUMERIC = 'numeric';      // max 24 digits

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @link https://dev.emarsys.com/v2/fields/create-a-field
     */
    public function createField(string $name, string $type): ResponseInterface
    {
        $request = new Request(
            'POST',
            '/field',
            [],
            json_encode(['name' => $name, 'application_type' => $type])
        );

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/fields/list-available-fields
     */
    public function getList(string $language = 'en'): ResponseInterface
    {
        $request = new Request(
            'GET',
            "/field/translate/{$language}"
        );

        return $this->client->sendRequest($request);
    }

    public function deleteField(int $fieldId): ResponseInterface
    {
        $request = new Request(
            'DELETE',
            "/field/{$fieldId}"
        );

        return $this->client->sendRequest($request);
    }
}
