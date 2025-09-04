<?php

namespace Developerayo\FireblocksLaravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
	/**
	 * register services
	 */
	public function register(): void
	{
		$this->mergeConfigFrom(
			__DIR__.'/../config/fireblocks.php',
			'fireblocks'
		);

		$this->app->singleton(Config::class, function () {
			$config = $this->app['config'];
			
			return new Config([
				'api_key' => $config->get('fireblocks.api_key'),
				'secret_key' => $config->get('fireblocks.secret_key'),
				'base_path' => $config->get('fireblocks.base_path'),
				'is_anonymous_platform' => $config->get('fireblocks.additional_options.is_anonymous_platform'),
				'user_agent' => $config->get('fireblocks.additional_options.user_agent'),
				'thread_pool_size' => $config->get('fireblocks.additional_options.thread_pool_size'),
				'debug' => $config->get('fireblocks.debug'),
				'default_headers' => $config->get('fireblocks.default_headers', []),
				'temp_folder_path' => $config->get('fireblocks.temp_folder_path'),
			]);
		});

		$this->app->singleton(Client::class, function () {
			$config = $this->app->make(Config::class);
			return new Client($config);
		});

		$this->app->singleton(Fireblocks::class, function () {
			return new Fireblocks();
		});

		$this->app->alias(Fireblocks::class, 'fireblocks');
	}

	/**
	 * bootstrap services
	 */
	public function boot(): void
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__.'/../config/fireblocks.php' => config_path('fireblocks.php'),
			], 'fireblocks-config');
		}
	}

	/**
	 * Get provided services
	 * 
	 * @return array
	 */
	public function provides(): array
	{
		return [
			'fireblocks',
			Fireblocks::class,
			Client::class,
			Config::class,
		];
	}
}
