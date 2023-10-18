<?php

namespace Hapio\Sdk\Repositories;

use GuzzleHttp\Exception\BadResponseException;
use Hapio\Sdk\Exceptions\ErrorException;
use Hapio\Sdk\Models\Project;

class ProjectRepository extends Repository
{
    /**
     * Get the project of the current API token.
     *
     * @return Project
     */
    public function getCurrentProject(): Project
    {
        try {
            $response = $this->client->get('project');
        } catch (BadResponseException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        }

        return new Project(json_decode($response->getBody(), true));
    }
}
