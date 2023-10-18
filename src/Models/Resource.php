<?php

namespace Hapio\Sdk\Models;

class Resource extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'name',
        'max_simultaneous_bookings',
        'metadata',
        'protected_metadata',
        'enabled',
        'created_at',
        'updated_at',
    ];
}
