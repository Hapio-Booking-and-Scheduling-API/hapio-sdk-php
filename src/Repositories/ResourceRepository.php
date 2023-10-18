<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Exception\BadResponseException;
use Hapio\Sdk\Exceptions\ErrorException;
use Hapio\Sdk\Exceptions\ValidationException;
use Hapio\Sdk\Models\Resource;
use Hapio\Sdk\Models\ResourceServiceAssociation;
use Hapio\Sdk\Models\TimeSpan;
use Hapio\Sdk\PaginatedResponse;

class ResourceRepository extends CrudRepository
{
    /**
     * Get the base path for the endpoints of the repository.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return 'resources';
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return Resource::class;
    }

    /**
     * List the schedule for a resource.
     *
     * @param string $resourceId The ID of the resource.
     * @param array  $params     The query parameters.
     *
     * @return PaginatedResponse
     */
    public function listSchedule(string $resourceId, array $params = []): PaginatedResponse
    {
        $path = static::getBasePath() . '/' . $resourceId . '/schedule';

        try {
            $response = $this->client->get($path, ['query' => $this->formatQueryParams($params)]);
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 422) {
                throw new ValidationException($e->getMessage(), $e->getCode(), $e);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $response = json_decode($response->getBody(), true);

        $response['data'] = array_map(function ($timeSpan) {
            return new TimeSpan($timeSpan);
        }, $response['data']);

        $followLinkCallback = function ($link) use ($response, $resourceId) {
            if (!isset($response['links'][$link])) {
                return null;
            }

            $queryString = parse_url($response['links'][$link], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            return $this->listSchedule($resourceId, $queryParams);
        };

        return new PaginatedResponse($response, $followLinkCallback);
    }

    /**
     * List the fully booked time spans for a resource.
     *
     * @param string $resourceId The ID of the resource.
     * @param array  $params     The query parameters.
     *
     * @return PaginatedResponse
     */
    public function listFullyBooked(string $resourceId, array $params = []): PaginatedResponse
    {
        $path = static::getBasePath() . '/' . $resourceId . '/fully-booked';

        try {
            $response = $this->client->get($path, ['query' => $this->formatQueryParams($params)]);
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 422) {
                throw new ValidationException($e->getMessage(), $e->getCode(), $e);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $response = json_decode($response->getBody(), true);

        $response['data'] = array_map(function ($timeSpan) {
            return new TimeSpan($timeSpan);
        }, $response['data']);

        $followLinkCallback = function ($link) use ($response, $resourceId) {
            if (!isset($response['links'][$link])) {
                return null;
            }

            $queryString = parse_url($response['links'][$link], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            return $this->listFullyBooked($resourceId, $queryParams);
        };

        return new PaginatedResponse($response, $followLinkCallback);
    }

    /**
     * Get a list of associated services.
     *
     * @param string $resourceId The ID of the resource.
     *
     * @return array
     */
    public function listAssociatedServices(string $resourceId): array
    {
        $path = static::getBasePath() . '/' . $resourceId . '/services';

        try {
            $response = $this->client->get($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $associations = json_decode($response->getBody(), true);

        $associations = array_map(function ($association) {
            return new ResourceServiceAssociation($association);
        }, $associations);

        return $associations;
    }

    /**
     * Get a single associated service.
     *
     * @param string $resourceId The ID of the resource.
     * @param string $serviceId  The ID of the service.
     *
     * @return ResourceServiceAssociation
     */
    public function getAssociatedService(string $resourceId, string $serviceId): ResourceServiceAssociation
    {
        $path = static::getBasePath() . '/' . $resourceId . '/services/' . $serviceId;

        try {
            $response = $this->client->get($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $association = json_decode($response->getBody(), true);

        return new ResourceServiceAssociation($association);
    }

    /**
     * Associate a service with a resource.
     *
     * @param string $resourceId The ID of the resource.
     * @param string $serviceId  The ID of the service.
     *
     * @return ResourceServiceAssociation
     */
    public function associateService(string $resourceId, string $serviceId): ResourceServiceAssociation
    {
        $path = static::getBasePath() . '/' . $resourceId . '/services/' . $serviceId;

        try {
            $response = $this->client->put($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return new ResourceServiceAssociation(json_decode($response->getBody(), true));
    }

    /**
     * Dissociate a service from a resource.
     *
     * @param string $resourceId The ID of the resource.
     * @param string $serviceId  The ID of the service.
     *
     * @return bool
     */
    public function dissociateService(string $resourceId, string $serviceId): bool
    {
        $path = static::getBasePath() . '/' . $resourceId . '/services/' . $serviceId;

        try {
            $response = $this->client->delete($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getStatusCode() === 204;
    }
}
