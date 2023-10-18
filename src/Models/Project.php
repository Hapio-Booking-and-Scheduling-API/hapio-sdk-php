<?php

namespace Hapio\Sdk\Models;

class Project extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'id',
        'name',
        'enabled',
        'created_at',
        'updated_at',
    ];
}
