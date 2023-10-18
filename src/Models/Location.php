<?php

namespace Hapio\Sdk\Models;

use DateTime;
use DateTimeZone;

class Location extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'name',
        'time_zone',
        'resource_selection_strategy',
        'resource_selection_priority',
        'metadata',
        'protected_metadata',
        'enabled',
        'created_at',
        'updated_at',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'time_zone' => DateTimeZone::class,
        'created_at' => DateTime::class,
        'updated_at' => DateTime::class,
    ];
}
