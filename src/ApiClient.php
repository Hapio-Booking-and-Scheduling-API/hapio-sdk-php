<?php

namespace Hapio\Sdk;

use GuzzleHttp\Client;
use Hapio\Sdk\Repositories\BookingRepository;
use Hapio\Sdk\Repositories\LocationRepository;
use Hapio\Sdk\Repositories\ProjectRepository;
use Hapio\Sdk\Repositories\RecurringScheduleBlockRepository;
use Hapio\Sdk\Repositories\RecurringScheduleRepository;
use Hapio\Sdk\Repositories\Repository;
use Hapio\Sdk\Repositories\ResourceRepository;
use Hapio\Sdk\Repositories\ScheduleBlockRepository;
use Hapio\Sdk\Repositories\ServiceRepository;

class ApiClient extends Client
{
    /**
     * The base URI to the API.
     *
     * @var string
     */
    const BASE_URI = 'https://eu-central-1.hapio.net/v1/';

    /**
     * The default timeout for requests.
     *
     * @var int
     */
    const TIMEOUT = 30;

    /**
     * The list of available repositories.
     *
     * @var array
     */
    protected $repositories = [
        'projects' => ProjectRepository::class,
        'locations' => LocationRepository::class,
        'resources' => ResourceRepository::class,
        'services' => ServiceRepository::class,
        'scheduleBlocks' => ScheduleBlockRepository::class,
        'recurringSchedules' => RecurringScheduleRepository::class,
        'recurringScheduleBlocks' => RecurringScheduleBlockRepository::class,
        'bookings' => BookingRepository::class,
    ];

    /**
     * The cached instances of repositories.
     *
     * @var array
     */
    protected $repositoryCache = [];

    /**
     * Constructor.
     * 
     * @param string $token The API token.
     */
    public function __construct(string $token)
    {
        parent::__construct([
            'base_uri' => self::BASE_URI,
            'timeout' => self::TIMEOUT,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Magic method to get repositories.
     *
     * If the name is not a valid repository, the call will be forwarded to the
     * parent class, i.e. GuzzleHttp\Client.
     *
     * @param string $name      The name of the method.
     * @param array  $arguments The arguments.
     *
     * @return Repository|mixed
     */
    public function __call($name, $arguments)
    {
        if (!array_key_exists($name, $this->repositories)) {
            return parent::__call($name, $arguments);
        }

        if (array_key_exists($name, $this->repositoryCache)) {
            return $this->repositoryCache[$name];
        }

        $class = $this->repositories[$name];

        $repository = new $class($this);

        $this->repositoryCache[$name] = $repository;

        return $repository;
    }
}
