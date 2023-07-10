<?php

namespace Guardian\Entity;

use GuzzleHttp\Client;

/**
 * Class APIEntity
 * 
 * Base class for all available guardian API entities
 * 
 * @package Guardian\Entity
 */
abstract class APIEntity
{
    /** @var string Query term to search for*/
    protected $query;
    /** @var string The base API URL for the API entity */
    protected $baseUrl;
    /** @var string The format to return the results in. */
    protected $format = "json";
    /** @var Client GuzzleHttp Client object to make the requests */
    private $client;

    /**
     * Construct from set parameters and return valid URL for requests
     * @return string Valid URL from set parameters
     */
    protected abstract function getUrl();

    /**
     * Creates instance of Guardian\Entity\APIEntity
     * @param string $baseUrl The base URL for this API entity with a valid API key
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        // guzzle client here
        $this->client = new Client();
    }

    /**
     * Fetches responses for this API entity from the guardian API endpoint
     * @param bool $asArray Specifies if response should be returned as a PHP object or an associative array
     * @return object|array
     */
    public final function fetch(bool $asArray = false)
    {
        $url = $this->getUrl();
        $headers = [
            "Accept" => "application/json"
        ];
        $response = $this->client->request('GET', $url, [
            'headers' => $headers
        ]);
        $result = $response->getBody()->getContents();
        return json_decode($result, $asArray);
    }

    /**
     * Sets the `query` attribute for this API entity
     * @param string $query Free text to search for
     * @return self The object this was set on
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
        return $this;
    }
}
