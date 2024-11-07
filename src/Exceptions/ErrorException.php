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
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        $response = $previous->getResponse();

        if (in_array('application/json', $response->getHeader('Content-Type'))) {
            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['messages']) {
                $messages = [];

                foreach ($responseBody['messages'] as $key => $value) {
                    $key = ucfirst(preg_replace('/\.(\d+)/', '[$1]', $key));

                    $messages[] = "$key: $value";
                }

                $message = implode(' ', $messages);
            } else {
                $message = $responseBody['messages'] ?? $responseBody['message'];
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
