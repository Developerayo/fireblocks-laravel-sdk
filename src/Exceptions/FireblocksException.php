<?php

namespace Developerayo\FireblocksLaravel\Exceptions;

use Exception;

class FireblocksException extends Exception
{
	protected array $responseData;
	protected array $responseHeaders;

	public function __construct(
		string $message = '',
		int $code = 0,
		array $responseData = [],
		array $responseHeaders = []
	) {
		parent::__construct($message, $code);
		$this->responseData = $responseData;
		$this->responseHeaders = $responseHeaders;
	}

	public function getResponseData(): array
	{
		return $this->responseData;
	}

	public function getResponseHeaders(): array
	{
		return $this->responseHeaders;
	}
}

// Base API exception (was ApiException)
class ApiException extends FireblocksException {}

// Specific HTTP status exceptions
class BadRequestException extends ApiException {}
class UnauthorizedException extends ApiException {}
class ForbiddenException extends ApiException {}
class NotFoundException extends ApiException {}
class ConflictException extends ApiException {}
class UnprocessableEntityException extends ApiException {}
class RateLimitException extends ApiException {}
class ServiceException extends ApiException {}