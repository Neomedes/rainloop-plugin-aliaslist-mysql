<?php

class Alias {

	/**
	 * @var string
	 */
	private $sEmailAliasUser;

	/**
	 * @var string
	 */
	private $sEmailAliasDomain;

	/**
	 * @param string $sEmailAlias
	 *
	 * @return \ChangeAliasListDriver
	 */
	public function __construct($sEmailAlias) {
		$aliasParts = splitEmail($sEmailAlias);
		$this->sEmailAliasUser = $aliasParts['user'];
		$this->sEmailAliasDomain = $aliasParts['domain'];
	}
	
	/**
	 * @var string $sEmailAddress
	 *
	 * @return array
	 */
	public static function splitEmail($sEmailAddress) {
		if(strpos($sEmailAddress, '@') === false) {
			return array(
				'user' => $sEmailAddress,
				'domain' => null
			);
		}
		$parts = explode('@', $sEmailAddress, 1);
		return array(
			'user' => $parts[0],
			'domain' => $parts[1]
		);
	}
	
	/**
	 * @var string $sEmailUser
	 * @var string $sEmailDomain
	 *
	 * @return string
	 */
	public static function joinEmail($sEmailUser, $sEmailDomain) {
		if($sEmailDomain === null || strlen($sEmailDomain) < 1) {
			return $sEmailUser
		}
		return implode('@', array($sEmailUser, $sEmailDomain));
	}

	/**
	 * @param string $sEmailAliasUser
	 *
	 * @return \Alias
	 */
	public function SetEmailAliasUser($sEmailAliasUser) {
		$this->sEmailAliasUser = $sEmailAliasUser;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetEmailAliasUser() {
		return $this->sEmailAliasUser;
	}

	/**
	 * @param string $sEmailAliasDomain
	 *
	 * @return \Alias
	 */
	public function SetEmailAliasDomain($sEmailAliasDomain) {
		$this->sEmailAliasDomain = $sEmailAliasDomain;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetEmailAliasDomain() {
		return $this->sEmailAliasDomain;
	}

}

?>
