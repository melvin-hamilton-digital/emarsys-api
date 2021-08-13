<?php

namespace MHD\Emarsys\Api;

use GuzzleHttp\Psr7\Request;
use MHD\Emarsys\Data\ContactFields;
use Psr\Http\Message\ResponseInterface;

/**
 * @link https://dev.emarsys.com/v2/contacts/
 */
class Contacts
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
     * @link https://dev.emarsys.com/v2/contacts/create-contacts
     */
    public function createContact(
        ContactFields $contact,
        int $keyId = ContactFields::ID_EMAIL
    ): ResponseInterface {
        $data = ['key_id' => $keyId] + $contact->getFields();
        $request = new Request(
            'POST',
            'contact',
            [],
            json_encode($data)
        );

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/contacts/update-contacts
     */
    public function updateContact(
        ContactFields $contact,
        $keyId = ContactFields::ID_EMAIL,
        bool $createIfNotExists = false
    ): ResponseInterface {
        $createIfNotExists = (int)$createIfNotExists;
        $data = ['key_id' => $keyId] + $contact->getFields();
        $request = new Request(
            'PUT',
            "contact/?create_if_not_exists={$createIfNotExists}",
            [],
            json_encode($data)
        );

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/contacts/list-contact-data
     * @note route provided in docs has no trailing slash but requires one to work
     */
    public function getList(
        int $return,
        int $limit = 10000,
        int $offset = 0
    ): ResponseInterface {
        $query = [
            'return' => $return,
            'limit' => $limit,
            'offset' => $offset,
        ];
        $request = new Request(
            'GET',
            'contact/query/?' . http_build_query($query)
        );

        return $this->client->sendRequest($request);
    }

    /**
     * @link https://dev.emarsys.com/v2/contacts/get-contact-data
     */
    public function getData(
        int $keyId = ContactFields::ID_EMAIL,
        array $keyValues = [],
        array $fields = []
    ): ResponseInterface {
        $payload = [
            'keyId' => $keyId,
            'keyValues' => $keyValues,
            'fields' => $fields
        ];
        $request = new Request(
            'POST',
            'contact/getdata',
            [],
            json_encode($payload)
        );

        return $this->client->sendRequest($request);
    }

    public function deleteContact(
        string $key,
        string $keyId = ContactFields::ID_EMAIL
    ): ResponseInterface {
        $payload = [
            'key_id' => $keyId,
            $keyId => $key
        ];
        $request = new Request(
            'POST',
            'contact/delete',
            [],
            json_encode($payload)
        );

        return $this->client->sendRequest($request);
    }
}
