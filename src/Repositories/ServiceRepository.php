<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Hapio\Sdk\Exceptions\ErrorException;
use Hapio\Sdk\Exceptions\ValidationException;
use Hapio\Sdk\Models\BookableSlot;
use Hapio\Sdk\Models\ResourceServiceAssociation;
use Hapio\Sdk\Models\Service;
use Hapio\Sdk\PaginatedResponse;

class ServiceRepository extends CrudRepository
{
    /**
     * Get the base path for the endpoints of the repository.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return 'services';
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return Service::class;
    }

    /**
     * List bookable slots for a service.
     *
     * @param string $serviceId The ID of the service.
     * @param array  $params    The query parameters.
     *
     * @return PaginatedResponse
     * @throws ErrorException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function listBookableSlots(string $serviceId, array $params = []): PaginatedResponse
    {
        $path = static::getBasePath() . '/' . $serviceId . '/bookable-slots';

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

        $response['data'] = array_map(function ($bookableSlot) {
            return new BookableSlot($bookableSlot);
        }, $response['data']);

        $followLinkCallback = function ($link) use ($response, $serviceId) {
            if (!isset($response['links'][$link])) {
                return null;
            }

            $queryString = parse_url($response['links'][$link], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            return $this->listBookableSlots($serviceId, $queryParams);
        };

        return new PaginatedResponse($response, $followLinkCallback);
    }

    /**
     * Get a list of associated resources.
     *
     * @param string $serviceId The ID of the service.
     *
     * @return array
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function listAssociatedResources(string $serviceId): array
    {
        $path = static::getBasePath() . '/' . $serviceId . '/resources';

        try {
            $response = $this->client->get($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $associations = json_decode($response->getBody(), true);

        return array_map(function ($association) {
            return new ResourceServiceAssociation($association);
        }, $associations);
    }

    /**
     * Get a single associated resource.
     *
     * @param string $serviceId  The ID of the service.
     * @param string $resourceId The ID of the resource.
     *
     * @return ResourceServiceAssociation
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function getAssociatedResource(string $serviceId, string $resourceId): ResourceServiceAssociation
    {
        $path = static::getBasePath() . '/' . $serviceId . '/resources/' . $resourceId;

        try {
            $response = $this->client->get($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $association = json_decode($response->getBody(), true);

        return new ResourceServiceAssociation($association);
    }

    /**
     * Associate a resource with a service.
     *
     * @param string $serviceId  The ID of the service.
     * @param string $resourceId The ID of the resource.
     *
     * @return ResourceServiceAssociation
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function associateResource(string $serviceId, string $resourceId): ResourceServiceAssociation
    {
        $path = static::getBasePath() . '/' . $serviceId . '/resources/' . $resourceId;

        try {
            $response = $this->client->put($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return new ResourceServiceAssociation(json_decode($response->getBody(), true));
    }

    /**
     * Dissociate a resource from a service.
     *
     * @param string $serviceId  The ID of the service.
     * @param string $resourceId The ID of the resource.
     *
     * @return bool
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function dissociateResource(string $serviceId, string $resourceId): bool
    {
        $path = static::getBasePath() . '/' . $serviceId . '/resources/' . $resourceId;

        try {
            $response = $this->client->delete($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getStatusCode() === 204;
    }
}
