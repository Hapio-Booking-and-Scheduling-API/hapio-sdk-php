<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Hapio\Sdk\Exceptions\ErrorException;
use Hapio\Sdk\Exceptions\ValidationException;
use Hapio\Sdk\Models\ModelInterface;
use Hapio\Sdk\PaginatedResponse;

abstract class NestedCrudRepository extends Repository implements NestedCrudRepositoryInterface
{
    use FormatsQueryParams;

    /**
     * Get a single model.
     *
     * @param array  $parentIds The ID of the parents of the model.
     * @param string $id        The ID of the model.
     *
     * @return ModelInterface|null
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function get(array $parentIds, string $id): ModelInterface|null
    {
        $path = static::getBasePath($parentIds) . '/' . $id;

        try {
            $response = $this->client->get($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $model = static::getModel();

        return new $model(json_decode($response->getBody(), true));
    }

    /**
     * Get a list of models.
     *
     * @param array $parentIds The ID of the parents of the model.
     * @param array $params    The query parameters.
     *
     * @return PaginatedResponse
     * @throws ErrorException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function list(array $parentIds, array $params = []): PaginatedResponse
    {
        $path = static::getBasePath($parentIds);

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

        $model = static::getModel();

        $response['data'] = array_map(function ($item) use ($model) {
            return new $model($item);
        }, $response['data']);

        $followLinkCallback = function ($link) use ($response, $parentIds) {
            if (!isset($response['links'][$link])) {
                return null;
            }

            $queryString = parse_url($response['links'][$link], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            return $this->list($parentIds, $queryParams);
        };

        return new PaginatedResponse($response, $followLinkCallback);
    }

    /**
     * Store a new model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param ModelInterface $model     The model to store.
     *
     * @return ModelInterface
     * @throws ErrorException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function store(array $parentIds, ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath($parentIds);

        try {
            $response = $this->client->post($path, ['json' => $model->toArray(true)]);
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 422) {
                throw new ValidationException($e->getMessage(), $e->getCode(), $e);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $model = static::getModel();

        return new $model(json_decode($response->getBody(), true));
    }

    /**
     * Replace an existing model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param string         $id        The ID of the model.
     * @param ModelInterface $model     The new model.
     *
     * @return ModelInterface
     * @throws ErrorException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function replace(array $parentIds, string $id, ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath($parentIds) . '/' . $id;

        try {
            $response = $this->client->put($path, ['json' => $model->toArray(true)]);
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 422) {
                throw new ValidationException($e->getMessage(), $e->getCode(), $e);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $model = static::getModel();

        return new $model(json_decode($response->getBody(), true));
    }

    /**
     * Patch an existing model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param string         $id        The ID of the model.
     * @param ModelInterface $model     The new model.
     *
     * @return ModelInterface
     * @throws ErrorException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function patch(array $parentIds, string $id, ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath($parentIds) . '/' . $id;

        try {
            $response = $this->client->patch($path, ['json' => $model->toArray(true)]);
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 422) {
                throw new ValidationException($e->getMessage(), $e->getCode(), $e);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $model = static::getModel();

        return new $model(json_decode($response->getBody(), true));
    }

    /**
     * Delete a model.
     *
     * @param array  $parentIds The ID of the parents of the model.
     * @param string $id        The ID of the model.
     *
     * @return bool
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function delete(array $parentIds, string $id): bool
    {
        $path = static::getBasePath($parentIds) . '/' . $id;

        try {
            $response = $this->client->delete($path);
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getStatusCode() === 204;
    }

    /**
     * Get the base path for the endpoints of the repository.
     *
     * @param array $parentIds The ID of the parents.
     *
     * @return string
     */
    abstract protected static function getBasePath(array $parentIds): string;

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    abstract protected static function getModel(): string;
}
