<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

Stan Scates
blr | further

stan@sc8s.com
blrfurther.com

Basic OAuth and caching layer for Seaofclouds' tweet.js, designed
to introduce compatibility with Twitter's v1.1 API.

Version: 1.4
Created: 2013.02.20

https://github.com/seaofclouds/tweet
https://github.com/themattharris/tmhOAuth

 */

class Twitter {
	/*************************************** config ***************************************/

   // Your Twitter App Consumer Key
	private $consumer_key = TWITTER_CONSUMER_KEY;

	// Your Twitter App Consumer Secret
	private $consumer_secret = TWITTER_CONSUMER_SECRET;

	// Your Twitter App Access Token
	private $user_token = TWITTER_USER_TOKEN;

	// Your Twitter App Access Token Secret
	private $user_secret = TWITTER_USER_SECRET;

	// Path to tmhOAuth libraries
	private $lib = 'libraries/twitter/';

	// Cache interval (minutes)
	private $cache_interval = 15;

	// Path to writable cache directory
	private $cache_dir = 'cache/';

	// Enable debugging
	private $debug = false;
	
	/**************************************************************************************/

	public function __construct() {
		$this->consoleDebug('constructor');
		
		// Initialize paths and etc.
		$this->cache_dir = APPPATH.$this->cache_dir;
		$this->lib = APPPATH.$this->lib;
		$this->message = '';

		// Set server-side debug params
		if($this->debug === true) {
			error_reporting(-1);
		} else {
			error_reporting(0);
		}
	}

	/*
	 * 
	 */
	public function fetch() 
	{
		echo json_encode(
			array(
				'response' => json_decode($this->_getJSON(), true),
				'message' => ($this->debug) ? $this->message : false
			)
		);
	}

	/*
	 * 
	 */
	private function _getJSON() 
	{
		$CFID = 'twitter_'.$this->_generateCFID();
		$cache_file = $this->cache_dir.$CFID;

		if(file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * intval($this->cache_interval)))) 
		{
			return file_get_contents($cache_file, FILE_USE_INCLUDE_PATH);
		} 
		else 
		{
			$JSONraw = $this->getTwitterJSON();
			$JSON = $JSONraw['response'];

			// Don't write a bad cache file if there was a CURL error
			if($JSONraw['errno'] != 0) 
			{
				$this->consoleDebug($JSONraw['error']);
				return $JSON;
			}

			if($this->debug === true) 
			{
				// Check for twitter-side errors
				$pj = json_decode($JSON, true);
				if(isset($pj['errors'])) 
				{
					foreach($pj['errors'] as $error) 
					{
						$message = 'Twitter Error: "'.$error['message'].'", Error Code #'.$error['code'];
						$this->consoleDebug($message);
					}
					return false;
				}
			}

			if(is_writable($this->cache_dir) && $JSONraw) {
				if(file_put_contents($cache_file, $JSON, LOCK_EX) === false) 
				{
					$this->consoleDebug("Error writing cache file");
				}
			} 
			else 
			{
				$this->consoleDebug("Cache directory is not writable");
			}
			return $JSON;
		}
	}

	/*
	 * 
	 */
	private function getTwitterJSON() 
	{
		require $this->lib.'tmhOAuth.php';
		require $this->lib.'tmhUtilities.php';

		$tmhOAuth = new tmhOAuth(array(
			'host'                  => $_POST['request']['host'],
			'consumer_key'          => $this->consumer_key,
			'consumer_secret'       => $this->consumer_secret,
			'user_token'            => $this->user_token,
			'user_secret'           => $this->user_secret,
			'curl_ssl_verifypeer'   => false
		));

		$url = $_POST['request']['url'];
		$params = $_POST['request']['parameters'];

		$tmhOAuth->request('GET', $tmhOAuth->url($url), $params);
		return $tmhOAuth->response;
	}
	
	/*
	 * 
	 */
	private function _generateCFID() 
	{
		// The unique cached filename ID
		return md5(serialize($_POST)).'.json';
	}

	private function consoleDebug($message) 
	{
		if($this->debug === true) {
			$this->message .= 'tweet.js: '.$message."\n";
		}
	}
}

?>
