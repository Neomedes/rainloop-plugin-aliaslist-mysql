<?php

class ChangeAliasListDriver {

	/**
	 * @var \MailSo\Log\Logger
	 */
	private $oLogger = null;

	// Database login data

	/**
	 * @var string
	 */
	private $sHost = '127.0.0.1';

	/**
	 * @var int
	 */
	private $iPort = 3306;

	/**
	 * @var string
	 */
	private $sDatabase = 'maildata';

	/**
	 * @var string
	 */
	private $sUser = 'mailuser';

	/**
	 * @var string
	 */
	private $sPassword = '';

	// Table data

	/**
	* @var string
	*/
	private $sTable = 'aliases';

	/**
	* @var string
	*/
	private $sColumnId = 'id';

	/**
	* @var string
	*/
	private $sColumnSourceUser = 'source_username';

	/**
	* @var string
	*/
	private $sColumnSourceDomain = 'source_domain';

	/**
	* @var string
	*/
	private $sColumnDestinationUser = 'destination_username';

	/**
	* @var string
	*/
	private $sColumnDestinationDomain = 'destination_domain';

	/**
	* @var string
	*/
	private $sColumnEnabled = 'enabled';

	/**
	 * @param \MailSo\Log\Logger $oLogger
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetLogger($oLogger) {
		if ($oLogger instanceof \MailSo\Log\Logger)
		{
			$this->oLogger = $oLogger;
		}

		return $this;
	}
	
	/**
	 * @param string $sMessage
	 * @param int $iType
	 * @param string $sName
	 * @param bool $bSearchSecretWords
	 * @param bool $bDisplayCrLf
	 */
	private function log($sMessage, $iType = \MailSo\Log\Enumerations\Type::INFO,
		$sName = '', $bSearchSecretWords = true, $bDisplayCrLf = false) {
		if ($this->oLogger) {
			$this->oLogger->Write($sMessage, $iType, $sName, $bSearchSecretWords, $bDisplayCrLf);
		}
	}

	/**
	 * @param \Exception $oException
	 */
	private function logException($oException) {
		if ($this->oLogger) {
			$this->oLogger->WriteException($oException);
		}
	}

	/**
	 * @param string $sHost
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetHost($sHost) {
		$this->sHost = $sHost;
		return $this;
	}

	/**
	 * @param int $iPort
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetPort($iPort) {
		$this->iPort = (int) $iPort;
		return $this;
	}

	/**
	 * @param string $sDatabase
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetDatabase($sDatabase) {
		$this->sDatabase = $sDatabase;
		return $this;
	}

	/**
	 * @param string $sUser
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetUser($sUser) {
		$this->sUser = $sUser;
		return $this;
	}

	/**
	 * @param string $sPassword
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function SetPassword($sPassword) {
		$this->sPassword = $sPassword;
		return $this;
	}

	/**
	* @param string $sTable
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetTable($sTable) {
		$this->sTable = $sTable;
		return $this;
	}

	/**
	* @param string $sColumnId
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnId($sColumnId) {
		$this->sColumnId = $sColumnId;
		return $this;
	}

	/**
	* @param string $sColumnSourceUser
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnSourceUser($sColumnSourceUser) {
		$this->sColumnSourceUser = $sColumnSourceUser;
		return $this;
	}

	/**
	* @param string $sColumnSourceDomain
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnSourceDomain($sColumnSourceDomain) {
		$this->sColumnSourceDomain = $sColumnSourceDomain;
		return $this;
	}

	/**
	* @param string $sColumnDestinationUser
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnDestinationUser($sColumnDestinationUser) {
		$this->sColumnDestinationUser = $sColumnDestinationUser;
		return $this;
	}

	/**
	* @param string $sColumnDestinationDomain
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnDestinationDomain($sColumnDestinationDomain) {
		$this->sColumnDestinationDomain = $sColumnDestinationDomain;
		return $this;
	}

	/**
	* @param string $sColumnEnabled
	*
	* @return \ChangeAliasListDriver
	*/
	public function SetColumnEnabled($sColumnEnabled) {
		$this->sColumnEnabled = $sColumnEnabled;
		return $this;
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 *
	 * @return bool
	 */
	public function ChangeAliasListPossibility($oAccount) {
		return $oAccount && $oAccount->Email();
	}
	
	/**
	 * @return \PDO
	 */
	private function openDatabaseConnection() {
		$sDsn = 'mysql:host='.$this->sHost.';port='.$this->iPort.';dbname='.$this->sDatabase;

		$oPdo = new \PDO($sDsn, $this->sUser, $this->sPassword);
		$oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $oPdo;
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 *
	 * @return array
	 */
	public function LoadAliasList(\RainLoop\Account $oAccount) {
		if($oAccount === null) {
			$this->log('AliasList: Unknown account!',
				   \MailSo\Log\Enumerations\Type::ERROR);
			return array();
		}
		$this->log('AliasList: Try to load alias list for '.$oAccount->Email());

		$aResult = array();
		include_once __DIR__.'/EmailAddress.php';
		$oEmail = new EmailAddress($oAccount->Email());

		try {
			$oPdo = $this->openDatabaseConnection();

			// loading aliases
			{
				$oStmt = $oPdo->prepare("SELECT {$this->sColumnSourceUser}, {$this->sColumnSourceDomain}
FROM {$this->sTable}
WHERE {$this->sColumnDestinationUser} = ?
AND   {$this->sColumnDestinationDomain} = ?
AND   {$this->sColumnEnabled} = true");
				$oStmt->execute(array($oEmail->GetUser(), $oEmail->GetDomain()));

				$aFields = $oStmt->fetchAll();
				for($a = 0; $a < \count($aFields); $a++) {
					$aResult[] = new EmailAddress($aFields[$a][0], $aFields[$a][1]);
				}
			}

			$oPdo = null;
		}
		catch (\Exception $oException) {
			$this->logException($oException);
		}

		return $aResult;
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 * @param array $aAliasList
	 *
	 * @return string
	 */
	public function SaveAliasList(\RainLoop\Account $oAccount, $aAliasList) {
		if($oAccount === null) {
			$this->log('AliasList: Unknown account!',
				   \MailSo\Log\Enumerations\Type::ERROR);
			return array();
		}
		$this->log('AliasList: Try to save alias list for '.$oAccount->Email());

		$sResult = '';
		include_once __DIR__.'/EmailAddress.php';
		$oEmail = new EmailAddress($oAccount->Email());

		try {
			$oPdo = $this->openDatabaseConnection();

			// Statement for deleting current alias list / marking all as disabled
			{
				$oStmt = $oPdo->prepare("UPDATE {$this->sTable} SET {$this->sColumnEnabled} = false WHERE {$this->sColumnDestinationUser} = ? AND {$this->sColumnDestinationDomain} = ? ");
				$bResult = (bool) $oStmt->execute(array($oEmail->GetUser(), $oEmail->GetDomain()));
			}

			// saving new aliases
			{
				$oStmt = $oPdo->prepare("INSERT INTO {$this->sTable} ({$this->sColumnSourceUser}, {$this->sColumnSourceDomain}, {$this->sColumnDestinationUser}, {$this->sColumnDestinationDomain}, {$this->sColumnEnabled}) values (?, ?, ?, ?, true)
				 ON DUPLICATE KEY UPDATE {$this->sColumnEnabled} = true");

				$nSuccessfullyUpdated = 0;
				for( $a = 0 ; $a < \count($aAliasList) ; ++$a ) {
					$bResult = $oStmt->execute(array(
							$aAliasList[$a]->GetUser(),
							$aAliasList[$a]->GetDomain(),
							$oEmail->GetUser(),
							$oEmail->GetDomain()));
					if ( $bResult ) {
						$nSuccessfullyUpdated++;
					}
				}
			}

			$oPdo = null;
		}
		catch (\Exception $oException) {
			$this->logException($oException);
		}

		return $sResult;
	}
}

?>
