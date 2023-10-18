<?php

namespace Hapio\Sdk\Models;

use DateTime;

class BookableSlot extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'starts_at',
        'ends_at',
        'min_ends_at',
        'buffer_starts_at',
        'buffer_ends_at',
        'min_buffer_ends_at',
        'resources',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'starts_at' => DateTime::class,
        'ends_at' => DateTime::class,
        'min_ends_at' => DateTime::class,
        'buffer_starts_at' => DateTime::class,
        'buffer_ends_at' => DateTime::class,
        'min_buffer_ends_at' => DateTime::class,
        'resources.*' => Resource::class,
    ];
}
