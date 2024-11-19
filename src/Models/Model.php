<?php

namespace Hapio\Sdk\Models;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

abstract class Model implements ModelInterface
{
    /**
     * The valid property names for the model.
     *
     * @var array
     */
    protected static $propertyNames = [];

    /**
     * The properties that should be cast to specific classes.
     *
     * @var array
     */
    protected static $casts = [
        'created_at' => DateTime::class,
        'updated_at' => DateTime::class,
    ];

    /**
     * The properties of the model.
     *
     * @var array
     */
    protected array $properties = [];

    /**
     * Constructor.
     *
     * @param array $properties The properties to fill the model with.
     */
    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            $property = $this->toSnakeCase($property);

            if (!in_array($property, static::$propertyNames)) {
                continue;
            }

            if (
                array_key_exists($property, static::$casts)
                && !is_object($value)
                && $value !== null
            ) {
                // Cast the item to an object based on its model.
                if ($value instanceof static::$casts[$property]) {
                    $this->{$property} = $value;
                } else {
                    $this->{$property} = new static::$casts[$property]($value);
                }

                continue;
            }

            if (
                array_key_exists($property . '.*', static::$casts)
                && is_array($value)
            ) {
                $className = static::$casts[$property . '.*'];

                // Cast each item to an object based on a common model.
                $this->{$property} = array_map(function ($item) use ($className) {
                    if ($item instanceof $className) {
                        return $item;
                    }

                    return new $className($item);
                }, $value);

                continue;
            }

            $this->{$property} = $value;
        }
    }

    /**
     * Get an array representation of the model.
     *
     * When the array is formatted for use as input, only the ID of related
     * models are kept in the array. Otherwise, the related model is also
     * converted to an array.
     *
     * @param bool $formatAsInput Whether to format the array for use as input.
     *
     * @return array
     */
    public function toArray(bool $formatAsInput = false): array
    {
        $properties = $this->properties;

        foreach ($properties as $property => $value) {
            if ($value instanceof DateTime) {
                $properties[$property] = $value->format(DateTimeInterface::W3C);
            } elseif ($value instanceof DateTimeZone) {
                $properties[$property] = $value->getName();
            } elseif ($value instanceof Model) {
                if (!$formatAsInput) {
                    $properties[$property] = $value->toArray();
                } else {
                    $properties[$property . '_id'] = $value->id;
                    unset($properties[$property]);
                }
            } elseif (is_array($value)) {
                $properties[$property] = array_map(function ($item) use ($formatAsInput) {
                    if ($item instanceof Model) {
                        return $item->toArray($formatAsInput);
                    } else {
                        return $item;
                    }
                }, $value);
            }
        }

        return $properties;
    }

    /**
     * Get a property value from the model.
     *
     * @param string $property The name of the property.
     *
     * @return mixed The value of the property.
     */
    public function __get(string $property)
    {
        $property = $this->toSnakeCase($property);

        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return null;
    }

    /**
     * Set a property value in the model.
     *
     * @param string $property The name of the property.
     * @param mixed  $value    The value to set.
     *
     * @return void
     */
    public function __set(string $property, mixed $value)
    {
        $property = $this->toSnakeCase($property);

        if (in_array($property, static::$propertyNames)) {
            $this->properties[$property] = $value;
        }
    }

    /**
     * Determine if a property is set in the model.
     *
     * @param string $property The name of the model.
     *
     * @return bool
     */
    public function __isset(string $property)
    {
        $property = $this->toSnakeCase($property);

        return isset($this->properties[$property]);
    }

    /**
     * Unset a property in the model.
     *
     * @param string $property The name of the model.
     *
     * @return void
     */
    public function __unset(string $property)
    {
        $property = $this->toSnakeCase($property);

        unset($this->properties[$property]);
    }

    /**
     * Convert a property name from camelCase to snake_case.
     *
     * @param string $property The property name in camelCase.
     *
     * @return string The property name in snake_case.
     */
    protected function toSnakeCase(string $property): string
    {
        $property = preg_replace('/(?<!^)[A-Z]/', '_$0', $property);

        return strtolower($property);
    }
}
