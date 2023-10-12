<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class PurchaseClientService
{
    /** @var string */
    protected $endpoint;

    protected $client;

    const TIMEOUT = 15;

    const CONNECT_TIMEOUT = 5;

    /**
     * PoBoxPurchaseService constructor.
     */
    public function __construct()
    {
        $this->endpoint = 'https://recruitment.alegra.com';

        $this->client = $this->createClient();
    }

    /**
     * @return GuzzleHttpClient
     */
    private function createClient(): GuzzleHttpClient
    {
        return new GuzzleHttpClient([
            'base_uri' => $this->endpoint,
            'headers'  => ['Content-Type' => 'application/json'],
            'verify'   => false,
            'timeout' => self::TIMEOUT,
            'connect_timeout' => self::CONNECT_TIMEOUT
        ]);
    }

    /**
     * @param $ingredient
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function createRequest($ingredient)
    {
        try {
            $params = [
                'query' => [
                    'ingredient' => "{$ingredient}",
                ],
            ];

            /** @var Response $response */
            $response = $this->client->get('api/farmers-market/buy', $params);
        } catch (Exception $e) {
            logger($e->getMessage());
            logger($e->getTraceAsString());
            throw new Exception("Error getting access token");
        }
        return json_decode($response->getBody()->getContents());
    }
}
