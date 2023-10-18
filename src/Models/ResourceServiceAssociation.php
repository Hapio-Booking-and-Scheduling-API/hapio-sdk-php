<?php

namespace Hapio\Sdk\Models;

class ResourceServiceAssociation extends Model
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [
        'resource_id',
        'service_id',
        'created_at',
    ];
}
