<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\ModelInterface;
use Hapio\Sdk\PaginatedResponse;

interface NestedCrudRepositoryInterface
{
    /**
     * Get a single model.
     *
     * @param array  $parentIds The ID of the parents of the model.
     * @param string $id        The ID of the model.
     *
     * @return ModelInterface|null
     */
    public function get(array $parentIds, string $id): ModelInterface|null;

    /**
     * Get a list of models.
     *
     * @param array $parentIds The ID of the parents of the models.
     * @param array $params The query parameters.
     *
     * @return PaginatedResponse
     */
    public function list(array $parentIds, array $params = []): PaginatedResponse;

    /**
     * Store a new model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param ModelInterface $model     The model to store.
     *
     * @return ModelInterface
     */
    public function store(array $parentIds, ModelInterface $model): ModelInterface;

    /**
     * Replace an existing model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param string         $id        The ID of the model.
     * @param ModelInterface $model     The new model.
     *
     * @return ModelInterface
     */
    public function replace(array $parentIds, string $id, ModelInterface $model): ModelInterface;

    /**
     * Patch an existing model.
     *
     * @param array          $parentIds The ID of the parents of the model.
     * @param string         $id        The ID of the model.
     * @param ModelInterface $model     The new model.
     *
     * @return ModelInterface
     */
    public function patch(array $parentIds, string $id, ModelInterface $model): ModelInterface;

    /**
     * Delete a model.
     *
     * @param array  $parentIds The ID of the parents of the model.
     * @param string $id        The ID of the model.
     *
     * @return bool
     */
    public function delete(array $parentIds, string $id): bool;
}
