<?php

namespace Developerayo\FireblocksLaravel;

/**
 * Core Fireblocks API manager
 */
class Fireblocks
{
	protected Client $apiClient;
	protected array $apiInstances = [];

	public function __construct(?Client $client = null)
	{
		$this->apiClient = $client ?? app(Client::class);
	}


    public function getApiUser()
    {
        return $this->getApiInstance('api_user', 'ApiUserApi');
    }

    public function getAssets()
    {
        return $this->getApiInstance('assets', 'AssetsApi');
    }

    public function getAuditLogs()
    {
        return $this->getApiInstance('audit_logs', 'AuditLogsApi');
    }

    public function getBlockchainsAssets()
    {
        return $this->getApiInstance('blockchains_assets', 'BlockchainsAssetsApi');
    }

    public function getCompliance()
    {
        return $this->getApiInstance('compliance', 'ComplianceApi');
    }

    public function getComplianceScreeningConfiguration()
    {
        return $this->getApiInstance('compliance_screening_configuration', 'ComplianceScreeningConfigurationApi');
    }

    public function getConsoleUser()
    {
        return $this->getApiInstance('console_user', 'ConsoleUserApi');
    }

    public function getContractInteractions()
    {
        return $this->getApiInstance('contract_interactions', 'ContractInteractionsApi');
    }

    public function getContractTemplates()
    {
        return $this->getApiInstance('contract_templates', 'ContractTemplatesApi');
    }

    public function getContracts()
    {
        return $this->getApiInstance('contracts', 'ContractsApi');
    }

    public function getCosignersBeta()
    {
        return $this->getApiInstance('cosigners_beta', 'CosignersBetaApi');
    }

    public function getDeployedContracts()
    {
        return $this->getApiInstance('deployed_contracts', 'DeployedContractsApi');
    }

    public function getEmbeddedWallets()
    {
        return $this->getApiInstance('embedded_wallets', 'EmbeddedWalletsApi');
    }

    public function getExchangeAccounts()
    {
        return $this->getApiInstance('exchange_accounts', 'ExchangeAccountsApi');
    }

    public function getExternalWallets()
    {
        return $this->getApiInstance('external_wallets', 'ExternalWalletsApi');
    }

    public function getFiatAccounts()
    {
        return $this->getApiInstance('fiat_accounts', 'FiatAccountsApi');
    }

    public function getGasStations()
    {
        return $this->getApiInstance('gas_stations', 'GasStationsApi');
    }

    public function getInternalWallets()
    {
        return $this->getApiInstance('internal_wallets', 'InternalWalletsApi');
    }

    public function getJobManagement()
    {
        return $this->getApiInstance('job_management', 'JobManagementApi');
    }

    public function getKeyLinkBeta()
    {
        return $this->getApiInstance('key_link_beta', 'KeyLinkBetaApi');
    }

    public function getKeysBeta()
    {
        return $this->getApiInstance('keys_beta', 'KeysBetaApi');
    }

    public function getNfts()
    {
        return $this->getApiInstance('nfts', 'NFTsApi');
    }

    public function getNetworkConnections()
    {
        return $this->getApiInstance('network_connections', 'NetworkConnectionsApi');
    }

    public function getOtaBeta()
    {
        return $this->getApiInstance('ota_beta', 'OTABetaApi');
    }

    public function getOffExchanges()
    {
        return $this->getApiInstance('off_exchanges', 'OffExchangesApi');
    }

    public function getPaymentsPayout()
    {
        return $this->getApiInstance('payments_payout', 'PaymentsPayoutApi');
    }

    public function getPolicyEditorV2Beta()
    {
        return $this->getApiInstance('policy_editor_v2_beta', 'PolicyEditorV2BetaApi');
    }

    public function getPolicyEditorBeta()
    {
        return $this->getApiInstance('policy_editor_beta', 'PolicyEditorBetaApi');
    }

    public function getResetDevice()
    {
        return $this->getApiInstance('reset_device', 'ResetDeviceApi');
    }

    public function getSmartTransfer()
    {
        return $this->getApiInstance('smart_transfer', 'SmartTransferApi');
    }

    public function getStaking()
    {
        return $this->getApiInstance('staking', 'StakingApi');
    }

    public function getSwapBeta()
    {
        return $this->getApiInstance('swap_beta', 'SwapBetaApi');
    }

    public function getTags()
    {
        return $this->getApiInstance('tags', 'TagsApi');
    }

    public function getTokenization()
    {
        return $this->getApiInstance('tokenization', 'TokenizationApi');
    }

    public function getTransactions()
    {
        return $this->getApiInstance('transactions', 'TransactionsApi');
    }

    public function getTravelRule()
    {
        return $this->getApiInstance('travel_rule', 'TravelRuleApi');
    }

    public function getUserGroupsBeta()
    {
        return $this->getApiInstance('user_groups_beta', 'UserGroupsBetaApi');
    }

    public function getUsers()
    {
        return $this->getApiInstance('users', 'UsersApi');
    }

    public function getVaults()
    {
        return $this->getApiInstance('vaults', 'VaultsApi');
    }

    public function getWeb3Connections()
    {
        return $this->getApiInstance('web3_connections', 'Web3ConnectionsApi');
    }

    public function getWebhooks()
    {
        return $this->getApiInstance('webhooks', 'WebhooksApi');
    }

    public function getWebhooksV2()
    {
        return $this->getApiInstance('webhooks_v2', 'WebhooksV2Api');
    }

    public function getWorkspaceStatusBeta()
    {
        return $this->getApiInstance('workspace_status_beta', 'WorkspaceStatusBetaApi');
    }

    public function getWhitelistIpAddresses()
    {
        return $this->getApiInstance('whitelist_ip_addresses', 'WhitelistIpAddressesApi');
    }


    public function __get(string $name)
    {
        $methodName = 'get' . str_replace('_', '', ucwords($name, '_'));
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }
        throw new \BadMethodCallException("API endpoint '{$name}' does not exist.");
    }

    /**
     * get or create api instance
     */
    private function getApiInstance(string $key, string $className)
    {
        if (!isset($this->apiInstances[$key])) {
            $fullClassName = 'Developerayo\\FireblocksLaravel\\Api\\' . $className;
            if (!class_exists($fullClassName)) {
                throw new \BadMethodCallException("API class '{$fullClassName}' not found.");
            }
            $this->apiInstances[$key] = new $fullClassName($this->apiClient);
        }
        return $this->apiInstances[$key];
    }

    public function getApiClient(): Client
    {
        return $this->apiClient;
    }

    public function close(): void
    {
        $this->apiInstances = [];
    }

    public function __destruct()
    {
        $this->close();
    }
}