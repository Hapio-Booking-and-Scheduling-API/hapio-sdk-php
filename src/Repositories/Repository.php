<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\ApiClient;

abstract class Repository
{
    /**
     * The API client to use for requests.
     *
     * @var ApiClient
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param ApiClient $client The API client to use for requests.
     */
    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }
}
