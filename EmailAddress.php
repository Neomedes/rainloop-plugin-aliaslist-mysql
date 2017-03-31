<?php

class EmailAddress {

	/**
	 * @var string
	 */
	private $sUser;

	/**
	 * @var string
	 */
	private $sDomain;

	/**
	 * @param string $sEmailAddress
	 *
	 * @return \EmailAddress
	 */
	public function __construct($sEmailAddress) {
		$parts = splitEmail($sEmailAddress);
		$this->sUser = $parts['user'];
		$this->sDomain = $parts['domain'];
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
			return $sEmailUser;
		}
		return implode('@', array($sEmailUser, $sEmailDomain));
	}

	/**
	 * @param string $sUser
	 *
	 * @return \EmailAddress
	 */
	public function SetUser($sUser) {
		$this->sUser = $sUser;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetUser() {
		return $this->sUser;
	}

	/**
	 * @param string $sDomain
	 *
	 * @return \EmailAddress
	 */
	public function SetDomain($sDomain) {
		$this->sDomain = $sDomain;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetDomain() {
		return $this->sDomain;
	}

}

?>
