<?php

namespace Developerayo\FireblocksLaravel;

use Firebase\JWT\JWT;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;
use Developerayo\FireblocksLaravel\Exceptions\ApiException;
use Developerayo\FireblocksLaravel\Exceptions\BadRequestException;
use Developerayo\FireblocksLaravel\Exceptions\UnauthorizedException;
use Developerayo\FireblocksLaravel\Exceptions\ForbiddenException;
use Developerayo\FireblocksLaravel\Exceptions\NotFoundException;
use Developerayo\FireblocksLaravel\Exceptions\ConflictException;
use Developerayo\FireblocksLaravel\Exceptions\UnprocessableEntityException;
use Developerayo\FireblocksLaravel\Exceptions\RateLimitException;
use Developerayo\FireblocksLaravel\Exceptions\ServiceException;

class Client
{
	protected ClientInterface $httpClient;
	protected Config $config;
	protected array $defaultHeaders;

	public function __construct(Config $config, ?ClientInterface $httpClient = null)
	{
		$this->config = $config;
		
		$this->httpClient = $httpClient ?? new GuzzleClient([
			'base_uri' => $config->getBasePath(),
			'timeout' => $config->getTimeout(),
			'connect_timeout' => $config->getConnectTimeout(),
			'http_errors' => false,
			'verify' => $config->getVerifySSL(),
		]);

		$this->defaultHeaders = [
			'User-Agent' => $this->getUserAgent(),
			'X-API-Key' => $config->getApiKey(),
		];
	} 

	/**
	 * Make auth request to api
	 * 
	 * @param string $method HTTP method
	 * @param string $endpoint API endpoint
	 * @param array $queryParams Query parameters
	 * @param array $requestHeaders Additional headers
	 * @param mixed $requestBody Request payload
	 * @param string $responseType Expected response type
	 * @param string|null $endpointPath Alternative endpoint path
	 * @param string|null $idempotencyKey Cstom idempotency key for post req
	 * @return array [response, statusCode, headers]
	 */
	public function makeRequest(
		string $method,
		string $endpoint,
		array $queryParams = [],
		array $requestHeaders = [],
		$requestBody = null,
		string $responseType = '\stdClass',
		?string $endpointPath = null,
		?string $idempotencyKey = null
	): array {
		$headers = array_merge($this->defaultHeaders, $requestHeaders);
		
		if ($method === 'POST' && $idempotencyKey !== null) {
    		$headers['Idempotency-Key'] = $idempotencyKey;
		}
		
		if ($requestBody !== null && !isset($headers['Content-Type'])) {
			$headers['Content-Type'] = 'application/json';
		}
		
		$pathWithQuery = $endpoint;
		if (!empty($queryParams)) {
			$pathWithQuery .= '?' . http_build_query($queryParams);
		}
		
		if ($endpointPath !== null) {
			$url = $endpointPath;
			if (!empty($queryParams)) {
				$url .= '?' . http_build_query($queryParams);
			}
		} else {
			$url = $this->config->getBasePath() . $pathWithQuery;
		}
		
		$tokenPath = $endpointPath !== null 
			? parse_url($endpointPath, PHP_URL_PATH) . (parse_url($endpointPath, PHP_URL_QUERY) ? '?' . parse_url($endpointPath, PHP_URL_QUERY) : '')
			: $pathWithQuery;
		
		$token = $this->generateToken($method, $tokenPath, $requestBody);
		$headers['Authorization'] = 'Bearer ' . $token;

		$requestPayload = null;
		if ($requestBody !== null) {
			$requestPayload = is_string($requestBody) ? $requestBody : json_encode($requestBody);
		}

		$request = new Request($method, $url, $headers, $requestPayload);

		try {
			$response = $this->httpClient->send($request);
			$statusCode = $response->getStatusCode();

			if ($statusCode < 200 || $statusCode > 299) {
				$responseBody = $response->getBody()->getContents();
				$this->handleApiError($statusCode, $responseBody, $response->getHeaders());
			}

			$responseBody = $response->getBody()->getContents();
			if ($responseType === '\SplFileObject') {
				$content = $responseBody;
			} else {
				$content = json_decode($responseBody, true);
			}

			return [$content, $statusCode, $response->getHeaders()];
		} catch (RequestException $e) {
			throw new \Exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function getConfig(): Config
	{
		return $this->config;
	}


	/**
	 * Generate JWT for api auth
	 */
	private function generateToken(string $httpMethod, string $requestPath, $requestBody = null): string
	{
		$currentTimestamp = time();
		$requestNonce = (string) Str::uuid();
		$tokenExpirationSeconds = 55;
		
		if (is_string($requestBody)) {
			$jsonEncodedBody = $requestBody;
		} else {
			$jsonEncodedBody = ($requestBody !== null) ? json_encode($requestBody) : '';
		}
		
		$requestBodyHash = hash('sha256', $jsonEncodedBody);
		
		$jwtPayload = [
			'uri' => $requestPath,
			'nonce' => $requestNonce,
			'iat' => $currentTimestamp,
			'exp' => $currentTimestamp + $tokenExpirationSeconds,
			'sub' => $this->config->getApiKey(),
			'bodyHash' => $requestBodyHash,
			'method' => strtoupper($httpMethod),
		];

		return JWT::encode($jwtPayload, $this->formatPrivateKey($this->config->getSecretKey()), 'RS256');
	}

	private function formatPrivateKey(string $rawPrivateKey): string
	{
		$cleanedKey = trim($rawPrivateKey);
		
		if (strpos($cleanedKey, '-----BEGIN') !== false) {
			return $cleanedKey;
		}
		
		return "-----BEGIN RSA PRIVATE KEY-----\n" . 
			   chunk_split($cleanedKey, 64, "\n") . 
			   "-----END RSA PRIVATE KEY-----";
	}

	private function getUserAgent(): string
	{
		$userAgent = sprintf('fireblocks-laravel/%s', '1.0.0');
		
		if (!$this->config->isAnonymousPlatform()) {
			$osType = PHP_OS_FAMILY;
			$osVersion = php_uname('r');
			$osArch = php_uname('m');
			
			$userAgent .= sprintf(' (%s %s; %s)', $osType, $osVersion, $osArch);
		}

		if ($customUserAgent = $this->config->getUserAgent()) {
			$userAgent = $customUserAgent . ' ' . $userAgent;
		}

		return $userAgent;
	}

	/**
	 * Handle api err
	 */
	private function handleApiError(int $statusCode, string $body, array $headers): void
	{
		$errorData = json_decode($body, true) ?? [];
		
		switch ($statusCode) {
			case 400:
				throw new BadRequestException($body, $statusCode, $errorData, $headers);
			case 401:
				throw new UnauthorizedException($body, $statusCode, $errorData, $headers);
			case 403:
				throw new ForbiddenException($body, $statusCode, $errorData, $headers);
			case 404:
				throw new NotFoundException($body, $statusCode, $errorData, $headers);
			case 409:
				throw new ConflictException($body, $statusCode, $errorData, $headers);
			case 422:
				throw new UnprocessableEntityException($body, $statusCode, $errorData, $headers);
			case 429:
				throw new RateLimitException($body, $statusCode, $errorData, $headers);
			default:
				if ($statusCode >= 500 && $statusCode <= 599) {
					throw new ServiceException($body, $statusCode, $errorData, $headers);
				}
				throw new ApiException($body, $statusCode, $errorData, $headers);
		}
	}

}
