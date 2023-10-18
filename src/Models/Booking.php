<?php

namespace Hapio\Sdk\Models;

use DateTime;

class Booking extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'resource_id',
        'resource',
        'service_id',
        'service',
        'location_id',
        'location',
        'price',
        'metadata',
        'protected_metadata',
        'is_temporary',
        'starts_at',
        'ends_at',
        'buffer_starts_at',
        'buffer_ends_at',
        'ignore_schedule',
        'ignore_fully_booked',
        'ignore_bookable_slots',
        'created_at',
        'updated_at',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'resource' => Resource::class,
        'service' => Service::class,
        'location' => Location::class,
        'starts_at' => DateTime::class,
        'ends_at' => DateTime::class,
        'buffer_starts_at' => DateTime::class,
        'buffer_ends_at' => DateTime::class,
        'created_at' => DateTime::class,
        'updated_at' => DateTime::class,
    ];
}
