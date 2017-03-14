
(function () {

	/**
	 * @constructor
	 */
	function AliasListSettings() {
	
		// TODO aliasList should be of a list-like type!
		this.aliasList = ko.observable('');

		// Is the data currently loaded, checked or saved?
		this.loading = ko.observable(false);
		this.checking = ko.observable(false);
		this.saving = ko.observable(false);

		// calculated funcs for different questions
		// can the data currently be saved?
		this.savingDisabled = ko.computed(function () {
			return this.loading() || this.checking() || this.saving();
		}, this);
		// can the data currently be checked?
		this.checkingDisabled = this.savingDisabled;
		// can the data currently be changed?
		this.changingEnabled = ko.computed(function () {
			return !this.loading() && !this.checking() && !this.saving();
		}, this);
	}

	AliasListSettings.prototype.checkAliasList = function () {
		var self = this;

		if (this.checkingDisabled()) {
			return false;
		}

		this.checking(true);

		window.rl.pluginRemoteRequest(function (sResult, oData) {

			self.checking(false);

			if (window.rl.Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				// call was successful
				// TODO now display the answer
			}
			else
			{
				// call failed
				// TODO
			}

		}, 'AjaxCheckAliasListData', {
			'AliasList': this.aliasList()
		});
	};

	AliasListSettings.prototype.saveAliasList = function () {
		var self = this;

		if (this.savingDisabled()) {
			return false;
		}

		this.saving(true);

		window.rl.pluginRemoteRequest(function (sResult, oData) {

			self.saving(false);

			if (window.rl.Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				// call was successful
				// TODO display an answer if necessary
			}
			else
			{
				// call failed
				// TODO
			}

		}, 'AjaxSaveAliasListData', {
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

		}, 'AjaxLoadAliasListData');

	};

	window.rl.addSettingsViewModel(AliasListSettings, 'AliasListSettingsTab',
		'ALIAS_LIST_SETTINGS_PLUGIN/TAB_NAME', 'alias_list');

}());
