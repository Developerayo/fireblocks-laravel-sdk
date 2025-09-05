<?php

namespace Developerayo\FireblocksLaravel;

class Config
{
	public string $apiKey;
	public string $secretKey;
	public string $basePath;
	public bool $isAnonymousPlatform;
	public ?string $userAgent;
	public int $threadPoolSize;
	public bool $debug;
	public array $defaultHeaders;
	public ?string $tempFolderPath;
	public int $timeout;
	public int $connectTimeout;
	public bool $verifySSL;

	// constants for base path 
	public const SANDBOX = 'https://sandbox-api.fireblocks.io/v1';
	public const US = 'https://api.fireblocks.io/v1';
	public const EU = 'https://eu-api.fireblocks.io/v1';
	public const EU2 = 'https://eu2-api.fireblocks.io/v1';

	public function __construct(array $config = [])
	{
		$this->apiKey = $config['api_key'] ?? config('fireblocks.api_key') ?? '';
		$this->secretKey = $config['secret_key'] ?? config('fireblocks.secret_key') ?? '';
		$this->basePath = $config['base_path'] ?? config('fireblocks.base_path') ?? self::US;
		$this->isAnonymousPlatform = $config['is_anonymous_platform'] ?? false;
		$this->userAgent = $config['user_agent'] ?? null;
		$this->threadPoolSize = $config['thread_pool_size'] ?? 10;
		$this->debug = $config['debug'] ?? config('app.debug', false);
		$this->defaultHeaders = $config['default_headers'] ?? [];
		$this->tempFolderPath = $config['temp_folder_path'] ?? null;
		$this->timeout = $config['timeout'] ?? config('fireblocks.timeout') ?? 30;
		$this->connectTimeout = $config['connect_timeout'] ?? config('fireblocks.connect_timeout') ?? 10;
		$this->verifySSL = $config['verify_ssl'] ?? config('fireblocks.verify_ssl') ?? true;
		
		$this->validate();
	}

	public function getApiKey(): string
	{
		return $this->apiKey;
	}

	public function getSecretKey(): string
	{
		return $this->secretKey;
	}

	public function getBasePath(): string
	{
		return $this->basePath;
	}

	public function isAnonymousPlatform(): bool
	{
		return $this->isAnonymousPlatform;
	}

	public function getUserAgent(): ?string
	{
		return $this->userAgent;
	}

	public function getThreadPoolSize(): int
	{
		return $this->threadPoolSize;
	}

	public function isDebug(): bool
	{
		return $this->debug;
	}

	public function getDefaultHeaders(): array
	{
		return $this->defaultHeaders;
	}

	public function getTempFolderPath(): ?string
	{
		return $this->tempFolderPath;
	}

	public function getTimeout(): int
	{
		return $this->timeout;
	}

	public function getConnectTimeout(): int
	{
		return $this->connectTimeout;
	}

	public function getVerifySSL(): bool
	{
		return $this->verifySSL;
	}

	public function setApiKey(string $apiKey): self
	{
		$this->apiKey = $apiKey;
		return $this;
	}

	public function setSecretKey(string $secretKey): self
	{
		$this->secretKey = $secretKey;
		return $this;
	}

	public function setBasePath(string $basePath): self
	{
		$this->basePath = $basePath;
		return $this;
	}

	public function setDebug(bool $debug): self
	{
		$this->debug = $debug;
		return $this;
	}

	public function setDefaultHeaders(array $headers): self
	{
		$this->defaultHeaders = $headers;
		return $this;
	}

	protected function validate(): void
	{
		if (!$this->apiKey) {
			throw new \InvalidArgumentException(
				'API key is required. Set FIREBLOCKS_API_KEY in your environment.'
			);
		}

		if (!$this->secretKey) {
			throw new \InvalidArgumentException(
				'Secret key is required. Set FIREBLOCKS_SECRET_KEY in your environment.'
			);
		}

		if (!$this->basePath) {
			throw new \InvalidArgumentException(
				'Base path is required. Set FIREBLOCKS_BASE_PATH in your environment.'
			);
		}
	}
}
