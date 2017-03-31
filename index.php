<?php

class CustomSettingsTabPlugin extends \RainLoop\Plugins\AbstractPlugin
{
	/**
	 * @return void
	 */
	public function Init() {
		$this->UseLangs(true); // start use langs folder

		$this->addJs('js/AliasListSettings.js'); // add js file

		$this->addAjaxHook('AjaxLoadAliasListData', 'AjaxLoadAliasListData');
		$this->addAjaxHook('AjaxSaveAliasListData', 'AjaxSaveAliasListData');

		$this->addTemplate('templates/AliasListSettingsTab.html');
	}

	/**
	 * @return string
	 */
	public function Supported() {
		if (!extension_loaded('pdo') || !class_exists('PDO'))
		{
			return 'The PHP exention PDO (mysql) must be installed to use this plugin';
		}
		$aDrivers = \PDO::getAvailableDrivers();
		if (!is_array($aDrivers) || !in_array('mysql', $aDrivers))
		{
			return 'The PHP exention PDO (mysql) must be installed to use this plugin';
		}
		return '';
	}
	
	/**
	 * @return \ChangeAliasListDriver
	 */
	private function openConnection() {
		$oDriver = new ChangeAliasListDriver();
		return $oDriver
			->SetHost($this->Config()->Get('plugin', 'host', ''))
			->SetDomain($this->Config()->Get('plugin', 'port', 3306))
			->SetDatabase($this->Config()->Get('plugin', 'database', ''))
			->SetTable($this->Config()->Get('plugin', 'table', ''))
			->SetUser($this->Config()->Get('plugin', 'user', ''))
			->SetPassword($this->Config()->Get('plugin', 'password', ''))
			->SetColumnId($this->Config()->Get('plugin', 'column_id', ''))
			->SetColumnSourceUser($this->Config()->Get('plugin', 'column_src_user', ''))
			->SetColumnSourceDomain($this->Config()->Get('plugin', 'column_src_domain', ''))
			->SetColumnDestinationUser($this->Config()->Get('plugin', 'column_dest_user', ''))
			->SetColumnDestinationDomain($this->Config()->Get('plugin', 'column_dest_domain', ''))
			->SetColumnEnabled($this->Config()->Get('plugin', 'column_enabled', ''));
	}
	
	private function getAccount() {
		$oManager = $this->Manager();
		if($oManager === null) {
			return null;
		}
		$oActions = $oManager->Actions();
		if($oActions === null) {
			return null;
		}
		return $oActions->getAccount();
	}

	/**
	 * @return array
	 */
	public function AjaxLoadAliasListData() {
		$aAliasList = $this->openConnection()->LoadAliasList($this->getAccount());

		$aAliasAddressList = array();
		for($a = 0; $a < \count($aAliasList); $a++) {
			$aAliasAddressList[] = $aAliasList[$a].GetFullAddress();
		}
		
		$sAliases = \implode("\n", $aAliasAddressList);
		
		\sleep(1);
		return $this->ajaxResponse(__FUNCTION__, array(
			'AliasList' => $sAliasList
		));
	}
	
	/**
	 * @return array
	 */
	public function AjaxSaveAliasListData() {
		$sAliases = $this->ajaxParam('AliasList');
		$aAliasAddressList = \explode("\n", $sAliases);
		
		$aAliasList = array();
		for($a = 0; $a < \count($aAliasAddressList); $a++) {
			$aAliasList[] = new EmailAddress($aAliasAddressList[$a]);
		}
		
		$sResponse = $this->openConnection()->SaveAliasList($this->getAccount(), $aAliasList);
		
		\sleep(1);
		return $this->ajaxResponse(__FUNCTION__, array(
			'error' => $sResponse
		));
	}

	/**
	 * @return array
	 */
	public function configMapping() {
		return array(
			\RainLoop\Plugins\Property::NewInstance('host')->SetLabel('MySQL Host')
				->SetDefaultValue('127.0.0.1'),
			\RainLoop\Plugins\Property::NewInstance('port')->SetLabel('MySQL Port')
				->SetType(\RainLoop\Enumerations\PluginPropertyType::INT)
				->SetDefaultValue(3306),
			\RainLoop\Plugins\Property::NewInstance('database')->SetLabel('MySQL Database')
				->SetDefaultValue('aliasdb'),
			\RainLoop\Plugins\Property::NewInstance('table')->SetLabel('MySQL table')
				->SetDefaultValue('aliases'),
			\RainLoop\Plugins\Property::NewInstance('user')->SetLabel('MySQL User')
				->SetDefaultValue('aliasuser'),
			\RainLoop\Plugins\Property::NewInstance('password')->SetLabel('MySQL Password')
				->SetType(\RainLoop\Enumerations\PluginPropertyType::PASSWORD)
				->SetDefaultValue(''),
			\RainLoop\Plugins\Property::NewInstance('column_id')->SetLabel('ID column')
				->SetDefaultValue('id'),
			\RainLoop\Plugins\Property::NewInstance('column_src_user')->SetLabel('Source user column')
				->SetDefaultValue('source_username'),
			\RainLoop\Plugins\Property::NewInstance('column_src_domain')->SetLabel('Source domain column')
				->SetDefaultValue('source_domain'),
			\RainLoop\Plugins\Property::NewInstance('column_dest_user')->SetLabel('Destination user column')
				->SetDefaultValue('destination_username'),
			\RainLoop\Plugins\Property::NewInstance('column_dest_domain')->SetLabel('Destination domain column')
				->SetDefaultValue('destination_domain'),
			\RainLoop\Plugins\Property::NewInstance('column_enabled')->SetLabel('Enabled column')
				->SetDefaultValue('enabled')
		);
	}
}

?>
