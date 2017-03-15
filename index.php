<?php

class CustomSettingsTabPlugin extends \RainLoop\Plugins\AbstractPlugin
{
	/**
	 * @return void
	 */
	public function Init()
	{
		$this->UseLangs(true); // start use langs folder

		$this->addJs('js/AliasListSettings.js'); // add js file

		$this->addAjaxHook('AjaxLoadAliasListData', 'AjaxLoadAliasListData');
		$this->addAjaxHook('AjaxSaveAliasListData', 'AjaxSaveAliasListData');

		$this->addTemplate('templates/AliasListSettingsTab.html');
	}

	/**
	 * @return array
	 */
	public function AjaxLoadAliasListData()
	{
		// TODO get user's data from DB
		$sAliasList = '';

		\sleep(1);
		return $this->ajaxResponse(__FUNCTION__, array(
			'AliasList' => $sAliasList
		));
	}
	
	/**
	 * @return array
	 */
	private function splitAliasList($sAliasList) {
		// TODO return array of splitted data
	}
	
	/**
	 * @return array
	 */
	public function AjaxSaveAliasListData()
	{
		$sAliasList = $this->ajaxParam('AliasList');
		$aAliasList = splitAliasList($sAliasList);

		// TODO save alias list to db and retrieve possible error message
		$sResponse = '';
		
		\sleep(1);
		return $this->ajaxResponse(__FUNCTION__, array(
			'error' => $sResponse
		));
	}

}

?>
