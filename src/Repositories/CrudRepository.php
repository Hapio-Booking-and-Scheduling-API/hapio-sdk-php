<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Exception\BadResponseException;
use Hapio\Sdk\Exceptions\ErrorException;
use Hapio\Sdk\Exceptions\ValidationException;
use Hapio\Sdk\Models\ModelInterface;
use Hapio\Sdk\PaginatedResponse;

abstract class CrudRepository extends Repository implements CrudRepositoryInterface
{
    use FormatsQueryParams;

    /**
     * Get a single model.
     *
     * @param string $id The ID of the model.
     *
     * @return ModelInterface|null
     */
    public function get(string $id): ModelInterface|null
    {
        $path = static::getBasePath() . '/' . $id;

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
     * @param array $params The query parameters.
     *
     * @return PaginatedResponse
     */
    public function list(array $params = []): PaginatedResponse
    {
        $path = static::getBasePath();

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

        $followLinkCallback = function ($link) use ($response) {
            if (!isset($response['links'][$link])) {
                return null;
            }

            $queryString = parse_url($response['links'][$link], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            return $this->list($queryParams);
        };

        return new PaginatedResponse($response, $followLinkCallback);
    }

    /**
     * Store a new model.
     *
     * @param ModelInterface $model The model to store.
     *
     * @return ModelInterface
     */
    public function store(ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath();

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
     * @param string         $id    The ID of the model.
     * @param ModelInterface $model The new model.
     *
     * @return ModelInterface
     */
    public function replace(string $id, ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath() . '/' . $id;

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
     * @param string         $id    The ID of the model.
     * @param ModelInterface $model The new model.
     *
     * @return ModelInterface
     */
    public function patch(string $id, ModelInterface $model): ModelInterface
    {
        $path = static::getBasePath() . '/' . $id;

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
     * @param string $id The ID of the model.
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        $path = static::getBasePath() . '/' . $id;

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
     * @return string
     */
    abstract protected static function getBasePath(): string;

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    abstract protected static function getModel(): string;
}
