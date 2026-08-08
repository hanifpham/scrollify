<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MangaDexApiException extends Exception
{
    protected ?int $statusCode;
    protected ?array $responseBody;

    public function __construct(
        string $message = 'MangaDex API error occurred',
        int $statusCode = 0,
        ?array $responseBody = null,
        ?Throwable $previous = null
    ) {
        $this->statusCode = $statusCode > 0 ? $statusCode : null;
        $this->responseBody = $responseBody;
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
