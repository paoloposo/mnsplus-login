<?php


/**
 * A simple class to verify a user's log-in credentials for MNS+ (http://mns.bildung-rp.de/).
 * Static class fields need to be adapted to the respective MNS+ instance.
 * 
 * Requires LDAP extension for PHP (http://php.net/manual/de/book.ldap.php).
 * 
 * (c) Paolo Poso
 */
class MNSPlusAuth {
	
	private static $ldapHost = 'HOST';
	private static $ldapPort = 389;
	private static $ldapDomain = 'DOMAIN';
	
	public static $ldapBaseDN = 'BASE_DN';
	
	private static $allowAnonymousLogin = false;
	
	private $ld;
	
	
	public function __construct () {
		
		// open connection to the server
		$this->ld = ldap_connect(self::$ldapHost);
		
		ldap_set_option($this->ld, LDAP_OPT_PROTOCOL_VERSION, 3);
	}
	
	public function __destruct () {
		
		// close connection to the server
		ldap_close($this->ld);
	}
	
	/**
	 * Attempts to login to MNS+ server with user-provided credentials.
	 * Returns true if successful, false otherwise.
	 */
	public function verifyIdentity ($username, $password) {
		
		// prevent ldap injection
		$username = ldapspecialchars($username);
		$password = ldapspecialchars($password);
		
		// make sure username and password are non-empty
		if (($username === '' or $password === '') and !self::$allowAnonymousLogin) {
			return false;
		}
		
		$ld = $this->ld;
		
		// throw an exception if the connection is not established
		if (!$ld) {
			throw new Exception('LDAP server not available.');
		}
		
		// try to log in the user with his provided credentials
		$bind = @ldap_bind($ld, $username.'@'.self::$ldapDomain, $password);
		
		
		if ($bind) {
			// credentials valid
			return true;
		}
		else {
			// credentials invalid
			return false;
		}
	}
	
}
