<?php

namespace Hapio\Sdk\Models;

class RecurringSchedule extends Model
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
        'start_date',
        'end_date',
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
    ];
}
