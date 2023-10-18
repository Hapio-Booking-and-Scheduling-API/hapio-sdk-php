<?php

namespace Hapio\Sdk\Models;

class RecurringScheduleBlock extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'weekday',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];
}
