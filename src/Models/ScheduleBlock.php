<?php

namespace Hapio\Sdk\Models;

use DateTime;

class ScheduleBlock extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'location_id',
        'location',
        'starts_at',
        'ends_at',
        'is_available',
        'created_at',
        'updated_at',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'location' => Location::class,
        'starts_at' => DateTime::class,
        'ends_at' => DateTime::class,
        'created_at' => DateTime::class,
        'updated_at' => DateTime::class,
    ];
}
