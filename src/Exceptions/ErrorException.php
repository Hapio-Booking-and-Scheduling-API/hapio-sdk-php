<?php

namespace Hapio\Sdk\Exceptions;

use Exception;
use Throwable;

class ErrorException extends Exception
{
    /**
     * Constructor.
     *
     * @param string         $message  The exception message.
     * @param int            $code     The exception code.
     * @param Throwable|null $previous The previous exception.
     */
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        $response = $previous->getResponse();

        if (in_array('application/json', $response->getHeader('Content-Type'))) {
            $responseBody = json_decode($response->getBody(), true);

            $message = $responseBody['message'];
        }

        parent::__construct($message, $code, $previous);
    }
}