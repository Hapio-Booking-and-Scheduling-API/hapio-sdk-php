<?php

namespace Hapio\Sdk;

use Error;
use GuzzleHttp\Client;
use Hapio\Sdk\Repositories\BookingGroupRepository;
use Hapio\Sdk\Repositories\BookingRepository;
use Hapio\Sdk\Repositories\LocationRepository;
use Hapio\Sdk\Repositories\ProjectRepository;
use Hapio\Sdk\Repositories\RecurringScheduleBlockRepository;
use Hapio\Sdk\Repositories\RecurringScheduleRepository;
use Hapio\Sdk\Repositories\Repository;
use Hapio\Sdk\Repositories\ResourceRepository;
use Hapio\Sdk\Repositories\ScheduleBlockRepository;
use Hapio\Sdk\Repositories\ServiceRepository;

/**
 * An API client for the Hapio API.
 *
 * @method ProjectRepository                projects()                Get the project repository.
 * @method LocationRepository               locations()               Get the location repository.
 * @method ResourceRepository               resources()               Get the resource repository.
 * @method ServiceRepository                services()                Get the service repository.
 * @method ScheduleBlockRepository          scheduleBlocks()          Get the schedule block repository.
 * @method RecurringScheduleRepository      recurringSchedules()      Get the recurring schedule repository.
 * @method RecurringScheduleBlockRepository recurringScheduleBlocks() Get the recurring schedule block repository.
 * @method BookingRepository                bookings()                Get the booking repository.
 * @method BookingGroupRepository           bookingGroups()           Get the booking group repository.
 */
class ApiClient
{
    /**
     * The base URI to the API.
     *
     * @var string
     */
    public const BASE_URI = 'https://eu-central-1.hapio.net/v1/';

    /**
     * The default timeout for requests.
     *
     * @var int
     */
    public const TIMEOUT = 30;

    /**
     * The HTTP client.
     *
     * @var Client
     */
    protected Client $httpClient;

    /**
     * The list of available repositories.
     *
     * @var array
     */
    protected array $repositories = [
        'projects' => ProjectRepository::class,
        'locations' => LocationRepository::class,
        'resources' => ResourceRepository::class,
        'services' => ServiceRepository::class,
        'scheduleBlocks' => ScheduleBlockRepository::class,
        'recurringSchedules' => RecurringScheduleRepository::class,
        'recurringScheduleBlocks' => RecurringScheduleBlockRepository::class,
        'bookings' => BookingRepository::class,
        'bookingGroups' => BookingGroupRepository::class,
    ];

    /**
     * The cached instances of repositories.
     *
     * @var array
     */
    protected array $repositoryCache = [];

    /**
     * Constructor.
     *
     * @param string $token The API token.
     */
    public function __construct(string $token)
    {
        $this->httpClient = new Client([
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
    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->repositories)) {
            throw new Error('Call to undefined method ' . __CLASS__ . '::' . $name . '()');
        }

        if (array_key_exists($name, $this->repositoryCache)) {
            return $this->repositoryCache[$name];
        }

        $class = $this->repositories[$name];

        $repository = new $class($this->httpClient);

        $this->repositoryCache[$name] = $repository;

        return $repository;
    }
}
