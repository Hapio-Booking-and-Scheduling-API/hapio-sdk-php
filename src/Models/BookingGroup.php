<?php

namespace Hapio\Sdk\Models;

use DateTime;

class BookingGroup extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'metadata',
        'protected_metadata',
        'bookings',
        'created_at',
        'updated_at',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'bookings.*' => Booking::class,
        'created_at' => DateTime::class,
        'updated_at' => DateTime::class,
    ];
}
