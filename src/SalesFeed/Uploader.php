<?php

namespace MHD\Emarsys\SalesFeed;

use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @link https://help.emarsys.com/hc/en-us/articles/213706429-Uploading-your-sales-data
 */
class Uploader
{
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $token;

    public function __construct(
        ClientInterface $httpClient,
        string          $merchantId,
        string          $token
    ) {
        $this->httpClient = $httpClient;
        $this->merchantId = $merchantId;
        $this->token = $token;
    }

    public function upload(string $salesCsv): ResponseInterface
    {
        $request = new Request(
            'POST',
            "https://admin.scarabresearch.com/hapi/merchant/{$this->merchantId}/sales-data/api",
            [
                "Authorization" => "Bearer: {$this->token}",
                "Content-type" => "text/csv",
                "Accept" => "text/plain",
            ],
            $salesCsv
        );

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            throw new Exception($response->getBody()->getContents());
        }

        return $response;
    }
}
