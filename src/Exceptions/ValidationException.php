<?php

namespace Hapio\Sdk\Exceptions;

use Throwable;

class ValidationException extends ErrorException
{
    /**
     * The validation errors.
     *
     * @var array
     */
    protected $validationErrors = [];

    /**
     * Constructor.
     *
     * @param string         $message  The exception message.
     * @param int            $code     The exception code.
     * @param Throwable|null $previous The previous exception.
     */
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $response = $previous->getResponse();

        if (in_array('application/json', $response->getHeader('Content-Type'))) {
            $responseBody = json_decode($response->getBody(), true);

            if (array_key_exists('errors', $responseBody)) {
                $this->validationErrors = $responseBody['errors'];
            } else {
                $this->validationErrors = [];
            }
        }
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}