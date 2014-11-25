<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 * Social Media Constants
 */
define("CURL_TIME_OUT",					"5");
define("BLOGGER_NAME",					"snibstonmuseum");
define("BLOGGER_ID",					"7197564171262496043");
define("FACEBOOK_PAGE",					"Snibston");
define("FACEBOOK_PAGE_ID",				"59715484435");
define("FACEBOOK_APP_ID",               getenv('FACEBOOK_APP_ID'));
define("FACEBOOK_SECRET",               getenv('FACEBOOK_SECRET'));
define("FLICKR_USER",					"75288749@N02"); // lccheritage
define("FLICKR_SET_SNIBSTON_HOME",		"72157632653435887");
define("FLICKR_SET_SNIBSTON_VISIT",		"72157632653541817");
define("FLICKR_SET_SNIBSTON_LEARN",		"72157632653608093");
define("FLICKR_SET_SNIBSTON_HIRE",		"72157633829392451");
define("FLICKR_SET_SNIBSTON_CENTURY",	"72157632793615575");
define("FLICKR_SET_SNIBSTON_EAT",		"72157632800229235");
define("FLICKR_SET_SNIBSTON_WHATS_ON",	"72157632805243954");
define("TWITTER_CONSUMER_KEY",          getenv('TWITTER_CONSUMER_KEY'));
define("TWITTER_CONSUMER_SECRET",       getenv('TWITTER_CONSUMER_SECRET'));
define("TWITTER_USER_TOKEN",            getenv('TWITTER_USER_TOKEN'));
define("TWITTER_USER_SECRET",           getenv('TWITTER_USER_SECRET'));
define("SMTP_USER",           			getenv('SMTP_USER'));
define("SMTP_PASS",						getenv('SMTP_PASS'));
define("SMTP_DEST",						getenv('SMTP_DEST'));
