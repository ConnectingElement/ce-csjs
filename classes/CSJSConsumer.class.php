<?php

class CSJSConsumer
{
	protected $api;
	protected $username 	= '';
	protected $password 	= '';
	protected $connection = '';
	public $lastResponse = null;
	
	const ACTION_INSERT = 'insert';
	const ACTION_UPDATE = 'update';
	const ACTION_UPSERT = 'upsert';
	const ACTION_NONE = 'none';
	
	const API_URL_DEV = 'https://cesystems.aaadev.com/cs/v1/';
	const API_URL_LIVE = 'https://www.ce-systems.co.uk/cs/v1/';
	
	const DATE_FORMAT = 'Y-m-d H:i:s';
	
	const ENUM_N = 'N';
	const ENUM_Y = 'Y';
	
	const MAILING_LIST_ACTION_HONOUR			= 'honour';
	const MAILING_LIST_ACTION_NONE				= 'none';
	const MAILING_LIST_ACTION_SUBSCRIBE			= 'subscribe';
	const MAILING_LIST_ACTION_UNSUBSCRIBE		= 'unsubscribe';
	
	const MAILING_LIST_TYPE_DEV = 'dev';
	const MAILING_LIST_TYPE_LIVE = 'live';
	
	const REQUEST_GET = 'GET';
	const REQUEST_POST = 'POST';
	
	const STATUS_ERROR = 'error';
	const STATUS_FAILURE = 'failure';
	const STATUS_PARTIAL = 'partial';
	const STATUS_SUCCESS = 'success';
	
	const TRIGGERS_AUTO = 'auto';
	const TRIGGERS_NONE = 'none';
	
	/**
	 * Constructs a new CSJSConsumer
	 * @param string $username The CSJS username to use to connect
	 * @param string $password The CSJS password to use to connect
	 */
	function __construct($username = null, $password = null)
	{
		// Set api url to the development server if aadev or .dev are present in the server name
		$this->api = (defined('CSJS_USE_DEV') && CSJS_USE_DEV) ? self::API_URL_DEV : self::API_URL_LIVE;

		$this->username = ($username === null && defined('CSJS_USERNAME')) ? CSJS_USERNAME : $username;
		$this->password = ($password === null && defined('CSJS_PASSWORD')) ? CSJS_PASSWORD : $password;

		// Connect to the CSJS
		$this->connection = curl_init();
		curl_setopt($this->connection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($this->connection, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->connection, CURLOPT_SSL_VERIFYPEER, 0);
	}
	
	/**
	 * Retrieves a guest from the CSJS
	 * @param string $uuid The guest's uuid
	 * @param int $accountID The id of the account in CSJS
	 * @param string|null $mailingListType The type of mailing list to retrieve the guest from (self::MAILING_LIST_TYPE_* or null for default)
	 * @param string|null $acceptPending Whether to accept pending data for a quicker response (self::ENUM_* or null for default)
	 * @param string|array|null $requiredFields A comma separated string or array of strings that must be included in the response (null is default)
	 * @return array|bool|null The response from CSJS or null if it was invalid JSON
	 */
	public function getGuest($uuid, $accountID, $mailingListType = null, $acceptPending = null, $requiredFields = null)
	{
		$queryString = array();
		if($mailingListType === null && self::isDevelopmentEnvironment())
			$queryString[] = 'mailingListType=' . self::MAILING_LIST_TYPE_DEV;
		else if($mailingListType == self::MAILING_LIST_TYPE_DEV || $mailingListType == self::MAILING_LIST_TYPE_LIVE)
			$queryString[] = 'mailingListType=' . $mailingListType;
			
		if($acceptPending === self::ENUM_N || $acceptPending === self::ENUM_Y)
			$queryString[] = 'acceptPending=' . $acceptPending;
		
		if(is_array($requiredFields))
			$queryString[] = implode(',', $requiredFields);
		else if($requiredFields !== null)
			$queryString[] = $requiredFields;
		
		$queryString = ($queryString) ? '?' . implode('&', $queryString) : '';
		return $this->request(sprintf('guest/%s/%d%s', $uuid, $accountID, $queryString), self::REQUEST_GET);
	}
	
	/**
	 * Checks whether the given guest exists in the given account
	 * @param string $uuid The guests uuid
	 * @param int $accountID The id of the account to look in
	 * @param string $mailingListType The type of mailing list to check (self::MAILING_LIST_TYPE_* - default is _DEV on dev)
	 * @return array|bool|null The CSJS response
	 */
	public function guestExists($uuid, $accountID, $mailingListType = null)
	{
		$queryString = array();
		if($mailingListType === null && self::isDevelopmentEnvironment())
			$queryString[] = 'mailingListType=' . self::MAILING_LIST_TYPE_DEV;
		else if($mailingListType == self::MAILING_LIST_TYPE_DEV || $mailingListType == self::MAILING_LIST_TYPE_LIVE)
			$queryString[] = 'mailingListType=' . $mailingListType;
		
		$queryString = ($queryString) ? '?' . implode('&', $queryString) : '';
		return $this->request(sprintf('guest/%s/exists/%d%s', $uuid, $accountID, $queryString), self::REQUEST_GET);
	}
	
	/**
	 * Checks whether the guest is subscribed to the given mailing list
	 * @param string $uuid The uuid of the guest
	 * @param int $mailingListID The mailing list to check their subscription to
	 * @return array|bool|null The CSJS response
	 */
	public function guestSubscribed($uuid, $mailingListID)
	{
		return $this->request(sprintf('guest/%s/subscribed/%d', $uuid, $mailingListID), self::REQUEST_GET);
	}
	
	/**
	 * Tests whether the server is a development server
	 * @return boolean Whether the server is a development server (true) or live (false)
	 */
	public static function isDevelopmentEnvironment()
	{
		return ((strpos($_SERVER['SERVER_NAME'], 'aaadev') !== false) || (strpos($_SERVER['SERVER_NAME'], '.dev') !== false));
		//return false;
	}
	
	/**
	 * Sends a request to CSJS
	 * @param string $methodURL The URL of the REST API to request
	 * @param string $method The type of request (self::REQUEST_* - default is self::REQUEST_GET)
	 * @param string $data The data to be POSTed to the URL
	 * @return array|bool|null The CSJS response
	 */
	public function request($methodURL, $method = self::REQUEST_GET, $data = null)
	{		
		curl_setopt($this->connection, CURLOPT_URL, $this->api . $methodURL);
		switch($method){
			case self::REQUEST_GET:
				curl_setopt($this->connection, CURLOPT_HTTPGET, true);
				break;
			case self::REQUEST_POST:
				if($data)
				{
					$data = array('data' => json_encode($data));
					if(defined('CSJS_DEBUG') && CSJS_DEBUG && function_exists('logError'))
						logError(print_r($data, true));
					curl_setopt($this->connection, CURLOPT_POST, true);
					curl_setopt($this->connection, CURLOPT_POSTFIELDS, $data); 
				} else {
					return false;
				}
				break;
			default:
				return false;
		}
				
		if(defined('CSJS_DEBUG') && CSJS_DEBUG)
		{
			//curl_setopt($this->connection, CURLOPT_CERTINFO, true);
			curl_setopt($this->connection, CURLOPT_VERBOSE, true);
			$fh = fopen('php://temp', 'rw+');
			curl_setopt($this->connection, CURLOPT_STDERR, $fh);
		}
		$this->lastResponse = curl_exec($this->connection);
		if(defined('CSJS_DEBUG') && CSJS_DEBUG)
		{
			logError($this->lastResponse);
			$error = ($this->lastResponse === false) ? sprintf("cUrl error (#%d): %s", curl_errno($this->connection), curl_error($this->connection)) : '';
			rewind($fh);
			$log = stream_get_contents($fh);
			fclose($fh);
			if(function_exists('logError'))
				logError($error . "\n\n" . $log);
		}
		curl_close($this->connection);
		return json_decode($this->lastResponse, true);
	}
	
	/**
	 * Tests the response to see if it was successful
	 * @param array|null $response The response from CSJS
	 * @return boolean Whether the response is a success
	 */
	public static function responseSuccess($response)
	{
		if(is_array($response) && array_key_exists('status', $response))
			return self::testSuccessful($response['status']);
		return false;
	}
	
	/**
	 * Sends a subscribe request for the given individuals
	 * @param array $subscribers An array of associative arrays containing guest details
	 * @return array|bool|null The response from CSJS or null if it was not valid JSON
	 */
	public function subscribe($subscribers)
	{
		return $this->request('subscribe', self::REQUEST_POST, $subscribers);
	}
	
	/**
	 * Tests whether the given value is success
	 * @param string $value The value to test
	 * @return boolean Whether the value was a success indicator
	 */
	public static function testSuccessful($value)
	{
		return ($value == self::STATUS_SUCCESS);
	}
}

?>