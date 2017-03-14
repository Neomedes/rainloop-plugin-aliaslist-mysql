
(function () {

	/**
	 * @constructor
	 */
	function AliasListSettings() {
	
		// TODO aliasList should be of a list-like type!
		this.aliasList = ko.observable('');

		// Is the data currently loaded or saved?
		this.loading = ko.observable(false);
		this.saving = ko.observable(false);

		// calculated func for the above question.
		this.savingOrLoading = ko.computed(function () {
			return this.loading() || this.saving();
		}, this);
	}

	AliasListSettings.prototype.customAjaxSaveData = function () {
		var self = this;

		if (this.saving()) {
			return false;
		}

		this.saving(true);

		window.rl.pluginRemoteRequest(function (sResult, oData) {

			self.saving(false);

			if (window.rl.Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				// save was successful
			}
			else
			{
				// save failed
			}

		}, 'AjaxSaveCustomUserData', {
			'AliasList': this.aliasList()
		});
	};

	// special function
	AliasListSettings.prototype.onBuild = function () {
		var self = this;

		this.loading(true);

		window.rl.pluginRemoteRequest(function (sResult, oData) {

			self.loading(false);

			if (window.rl.Enums.StorageResultType.Success === sResult && oData && oData.Result) {
				self.aliasList(oData.Result.AliasList || '');
			}

		}, 'AjaxGetCustomUserData');

	};

	window.rl.addSettingsViewModel(AliasListSettings, 'AliasListSettingsTab',
		'ALIAS_LIST_SETTINGS/TAB_NAME', 'alias_list');

}());
