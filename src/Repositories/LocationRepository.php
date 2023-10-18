<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\Location;

class LocationRepository extends CrudRepository
{
    /**
     * Get the base path for the endpoints of the repository.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return 'locations';
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return Location::class;
    }
}
