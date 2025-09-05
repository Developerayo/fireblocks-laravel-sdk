# Fireblocks Laravel SDK

[![Latest Stable Version](https://poser.pugx.org/developerayo/fireblocks-laravel-sdk/v/stable.svg)](https://packagist.org/packages/developerayo/fireblocks-laravel-sdk)
[![License](https://poser.pugx.org/developerayo/fireblocks-laravel-sdk/license.svg)](LICENSE.md)

> A Laravel Package for working with Fireblocks API

## Installation

[PHP](https://php.net) 8.1+ and [Composer](https://getcomposer.org) are required. Laravel 10+ or 11+ should be fine.

To get the latest version of Fireblocks Laravel SDK, simply require it

```bash
composer require developerayo/fireblocks-laravel-sdk
```

Or add the following line to the require block of your `composer.json` file.

```json
"developerayo/fireblocks-laravel-sdk": "0.1.*"
```

## Configuration

You can publish the configuration file using:

```bash
php artisan vendor:publish --provider="Developerayo\FireblocksLaravel\ServiceProvider"
```

A configuration-file named `fireblocks.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [
    'api_key' => env('FIREBLOCKS_API_KEY', ''),
    'secret_key' => env('FIREBLOCKS_SECRET_KEY', ''),
    'base_path' => env('FIREBLOCKS_BASE_PATH', 'https://api.fireblocks.io/v1'),
    'is_anonymous_platform' => env('FIREBLOCKS_ANONYMOUS_PLATFORM', false),
    'user_agent' => env('FIREBLOCKS_USER_AGENT', null),
    'thread_pool_size' => env('FIREBLOCKS_THREAD_POOL_SIZE', 10),
    'default_headers' => [],
    'temp_folder_path' => env('FIREBLOCKS_TEMP_FOLDER', null),
    'timeout' => env('FIREBLOCKS_TIMEOUT', 30),
    'connect_timeout' => env('FIREBLOCKS_CONNECT_TIMEOUT', 10),
    'verify_ssl' => env('FIREBLOCKS_VERIFY_SSL', true),
    'debug' => env('FIREBLOCKS_DEBUG', false),
];
```

## Usage

Open your .env file and add your api key, secret key, and base path like:

```php
FIREBLOCKS_API_KEY=xxxxxxxx
FIREBLOCKS_SECRET_KEY=xxxxxxxx
FIREBLOCKS_BASE_PATH=https://sandbox-api.fireblocks.io/v1
FIREBLOCKS_BASE_PATH=https://api.fireblocks.io/v1
```

### Available API Endpoints

```php
<?php

use Fireblocks;

$vaults = Fireblocks::getVaults();
$transactions = Fireblocks::getTransactions();
$assets = Fireblocks::getBlockchainsAssets();
$externalWallets = Fireblocks::getExternalWallets();
$internalWallets = Fireblocks::getInternalWallets();
$exchangeAccounts = Fireblocks::getExchangeAccounts();
$fiatAccounts = Fireblocks::getFiatAccounts();
$networkConnections = Fireblocks::getNetworkConnections();
$webhooks = Fireblocks::getWebhooks();
$gasStations = Fireblocks::getGasStations();
$nfts = Fireblocks::getNfts();
$staking = Fireblocks::getStaking();
...
```

### Managing Assets

```php
<?php

use Fireblocks;

// get all supported assets
$assetsApi = Fireblocks::getBlockchainsAssets();
$supportedAssets = $assetsApi->getSupportedAssets();

foreach($supportedAssets as $asset) {
    echo "Asset ID: " . $asset['id'] . "\n";
    echo "Asset Name: " . $asset['name'] . "\n";
    echo "Asset Type: " . $asset['type'] . "\n";
}

// get your vault account assets
$vaultsApi = Fireblocks::getVaults();
$vaultAssets = $vaultsApi->getVaultAccountAsset($vaultAccountId, $assetId);

// create a new asset wallet
$vaultsApi->createVaultAccountAsset($vaultAccountId, $assetId);
```

### Using Magic Property Access

The SDK also supports magic property access:

```php
<?php

use Fireblocks;

$vaults = Fireblocks::vaults;
$transactions = Fireblocks::transactions;
$webhooks = Fireblocks::webhooks;
$nfts = Fireblocks::nfts;
$staking = Fireblocks::staking;

// use them directly
$vaultAccounts = Fireblocks::vaults->getPagedVaultAccounts();
$txList = Fireblocks::transactions->getTransactions();
```

## Available Regions

SDK currently supports multiple Fireblocks regions:

```php
use Developerayo\FireblocksLaravel\Config;

Config::US // https://api.fireblocks.io/v1 - (default)
Config::EU // https://eu-api.fireblocks.io/v1
Config::EU2 // https://eu2-api.fireblocks.io/v1
Config::SANDBOX // https://sandbox-api.fireblocks.io/v1
```

## Contributing

Please feel free to fork this package and contribute by submitting a pull request.

## How can I thank you?

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter(X) or HackerNews? Spread the word!

Don't forget to [follow me on twitter](https://twitter.com/developerayo)!

Thanks!
[Shodipo Ayomide](https://shodipoayomide.com)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
