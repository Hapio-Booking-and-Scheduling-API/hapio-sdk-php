<?php

namespace Hapio\Sdk\Models;

use DateTime;

class TimeSpan extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'starts_at',
        'ends_at',
    ];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'starts_at' => DateTime::class,
        'ends_at' => DateTime::class,
    ];
}
