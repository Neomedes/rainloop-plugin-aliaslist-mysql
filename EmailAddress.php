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
	 * @param string $sEmailUser
	 * @param string $sEmailDomain
	 *
	 * @return \EmailAddress
	 */
	public function __construct($sEmailFullAddressOrUser, $sEmailDomain = null) {
		if ( $sEmailDomain === null ) {
			// first parameter is full address
			$parts = EmailAddress::splitEmail($sEmailFullAddressOrUser);
        	        $this->sUser = $parts['user'];
	                $this->sDomain = $parts['domain'];
		}
		else {
			// full address is only user
			$this->sUser = $sEmailFullAddressOrUser;
			$this->sDomain = $sEmailDomain;
		}
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
		$parts = explode('@', $sEmailAddress, 2);
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
	
	/**
	 * @return string
	 */
	public function GetFullAddress() {
		return EmailAddress::joinEmail($this->sUser, $this->sDomain);
	}
	
}

?>
