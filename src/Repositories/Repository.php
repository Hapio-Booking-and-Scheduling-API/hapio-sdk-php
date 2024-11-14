<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Client;

abstract class Repository
{
    /**
     * The HTTP client to use for requests.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Constructor.
     *
     * @param Client $client The HTTP client to use for requests.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
