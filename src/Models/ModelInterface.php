<?php

namespace Hapio\Sdk\Models;

interface ModelInterface
{
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
    public function toArray(bool $formatAsInput = false): array;
}
