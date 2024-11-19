<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\BookingGroup;

class BookingGroupRepository extends CrudRepository
{
    /**
     * Get the base path for the endpoints of the repository.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return 'booking-groups';
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return BookingGroup::class;
    }
}
