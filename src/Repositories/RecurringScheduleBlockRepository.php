<?php

namespace Hapio\Sdk\Repositories;

use Hapio\Sdk\Models\RecurringScheduleBlock;

class RecurringScheduleBlockRepository extends NestedCrudRepository
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
        return "resources/{$parentIds[0]}/recurring-schedules/{$parentIds[1]}/schedule-blocks";
    }

    /**
     * Get the class name of the model to use for the repository.
     *
     * @return string
     */
    protected static function getModel(): string
    {
        return RecurringScheduleBlock::class;
    }
}
