<?php

namespace Hapio\Sdk\Models;

class Service extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'name',
        'price',
        'type',
        'duration',
        'min_duration',
        'max_duration',
        'default_duration',
        'duration_step',
        'start_time',
        'end_time',
        'min_days',
        'max_days',
        'default_days',
        'bookable_interval',
        'buffer_time_before',
        'buffer_time_after',
        'booking_window_start',
        'booking_window_end',
        'cancelation_threshold',
        'metadata',
        'protected_metadata',
        'enabled',
        'created_at',
        'updated_at',
    ];
}
