<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\RecurringSchedule;

class RecurringScheduleRepository extends NestedCrudRepository
{
    /**
     * Get the base path for the endpoints of the repository.
     *
     * @param array $parentIds The ID of the parents.
     *
     * @return string
     */
    protected static function getBasePath(array $parentIds): string
    {
        return "resources/$parentIds[0]/recurring-schedules";
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return RecurringSchedule::class;
    }
}
