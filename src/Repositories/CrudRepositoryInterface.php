<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\ModelInterface;
use Hapio\Sdk\PaginatedResponse;

interface CrudRepositoryInterface
{
    /**
     * Get a single model.
     *
     * @param string $id The ID of the model.
     *
     * @return ModelInterface|null
     */
    public function get(string $id): ModelInterface|null;

    /**
     * Get a list of models.
     *
     * @param array $params The query parameters.
     *
     * @return PaginatedResponse
     */
    public function list(array $params = []): PaginatedResponse;

    /**
     * Store a new model.
     *
     * @param ModelInterface $model The model to store.
     *
     * @return ModelInterface
     */
    public function store(ModelInterface $model): ModelInterface;

    /**
     * Replace an existing model.
     *
     * @param string         $id    The ID of the model.
     * @param ModelInterface $model The new model.
     *
     * @return ModelInterface
     */
    public function replace(string $id, ModelInterface $model): ModelInterface;

    /**
     * Patch an existing model.
     *
     * @param string         $id    The ID of the model.
     * @param ModelInterface $model The new model.
     *
     * @return ModelInterface
     */
    public function patch(string $id, ModelInterface $model): ModelInterface;

    /**
     * Delete a model.
     *
     * @param string $id The ID of the model.
     *
     * @return bool
     */
    public function delete(string $id): bool;
}
