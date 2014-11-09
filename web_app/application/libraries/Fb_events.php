<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

//include 'ChromePhp.php';// For use with Chrome Logger (http://craig.is/writing/chrome-logger)
include(APPPATH.'libraries/facebook/facebook.php');
date_default_timezone_set('Europe/London');

/*
 * The Facebook Graph will not do "order_by" so use FQL to get the events instead.
 */
class Fb_events 
{
	private $_CI;
	private $_facebookAPI;
	private $_facebookEventsData;
	
	/*
	 * Set the Facebook API paramaters and load the driver for caching and the email helper.
	 */
	function __construct()
	{
		$this->facebookAPI = new Facebook(array(
				'appId'  => FACEBOOK_APP_ID,
				'secret' => FACEBOOK_SECRET
		));
		// We will be caching the data
		$this->CI =& get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc'));
		$this->_facebookEventsData = $this->getFacebookEvents();
	}
	
	/*
	 * Get the Facebook events.  We assume that the creator is the page_id.
	 * This might not always be true but currently it seems to be the case
	 * no matter who created the event. 
	 * 
	 * If you can't, then go to the cache (hopefully there is 
	 * a cached version) and use that instead.  Warn the web master that you could not
	 * get the events from Facebook.  The period of grace is...
	 * 
	 * 		(CACHE_TIME * CACHE_TIME_MULTIPLIER) - CACHE_TIME
	 * 
	 * ...because this is when the data cache will expire.
	 * 
	 * Get all the live events either they have not ended, or if there is
	 * no end date the start date has passed (so the start is a proxy for the end date).
	 */
	private function getFacebookEvents()
	{
		$cache_name = "facebook_events.json";
		$cache_data = null;
		try 
		{
			$fql = "SELECT eid, name, pic, start_time, end_time, description
            FROM event WHERE creator = ".FACEBOOK_PAGE_ID." 
			AND (end_time > now() OR start_time > now()) ORDER BY start_time asc LIMIT 40";
			$param  =   array(	"method"    => "fql.query",
								"query"     => $fql,
								"callback"  => "");
			$cache_data = $this->facebookAPI->api($param);
			$this->CI->cache->save($cache_name, $cache_data, CACHE_TIME_DATA);
		} 
		catch (Exception $e) 
		{
			$cache_data = $this->facebookEventsData = $this->CI->cache->get($cache_name);
			tell_webmaster("Facebook events not working.");
		}
		return $cache_data;
	}

	/*
	 * Get the Facebook events
	 */
	function getEventsList()
	{
		$eventsList = new Facebook_Events_List();
		$eventsList->events = $this->_getEvents();
		$eventsList->sourceUrl = $this->_getSourceUrl();
		return $eventsList;
	}

	/*
	 * Get a filtered set of events based on how the title string ends
	 */
	function getEventsListFilteredOnEnd($nameEnd)
	{
		$eventsList = new Facebook_Events_List();
		$eventsList->events = array_filter($this->_getEvents(), array(new Event_Name_Filter($nameEnd), "filterNameEnd"));
		$eventsList->sourceUrl = $this->_getSourceUrl();
		return $eventsList;
	} 
	
	/*
	 * Get a filtered set of events based on how the title string starts, if there
	 * is nothing use the next three events.
	 */
	function getEventsListFilteredOnStart($nameStart)
	{
		$eventsList = new Facebook_Events_List();
		$eventsList->events = array_filter($this->_getEvents(), array(new Event_Name_Filter($nameStart), "filterNameStart"));
		if (empty($eventsList->events))
		{
			$eventsList->events = array_slice($this->_getEvents(), 0, 3);
		}
		$eventsList->sourceUrl = $this->_getSourceUrl();
		return $eventsList;
	}
	
	/*
	 * Get the first three Facebook events
	 */
	function getFirstThreeEvents()
	{
		$eventsList = new Facebook_Events_List();
		$eventsList->events = array_slice($this->_getEvents(), 0, 3);
		$eventsList->sourceUrl = $this->_getSourceUrl();
		return $eventsList;
	}
	
	/*
	 * Get the events as objects rather than an array.
	 */
	private function _getEvents()
	{
		$events = array();
		foreach ($this->_facebookEventsData as $event){
			$facebookEvent = new Facebook_Event();
			$facebookEvent->name = $this->_htmlallentities($event['name']);
			$facebookEvent->pic = $event['pic'];
			$facebookEvent->when = $this->_prettyPrintDate($event['start_time'], $event['end_time']); // start_time and end_time
			$description = $this->_htmlallentities($event['description']);
			$facebookEvent->descriptionShort = nl2br($this->_truncate($description));
			$facebookEvent->descriptionLong = nl2br($this->_txt2link($description));
			$facebookEvent->url= "https://www.facebook.com/events/".$event['eid']; // eid;
			array_push($events, $facebookEvent);
		}
		return $events;
	}
	
	/*
	 * Return the URL of the facebook events.
	 */
	private function _getSourceUrl()
	{
		return "https://www.facebook.com/".FACEBOOK_PAGE."/events";
	}
	
	/*
	 * Truncate the description neatly, to the word closest to a certain number of characters
	 */
	private function _truncate($description)
	{
		$desiredWidth = 260;
		$parts = preg_split('/([\s\n\r]+)/', $description, null, PREG_SPLIT_DELIM_CAPTURE);
		$parts_count = count($parts);
	
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen($parts[$last_part]);
			if ($length > $desiredWidth) { break; }
		}
		return implode(array_slice($parts, 0, $last_part));
	}
	
	/*
	 * Tidy up the dates so they look neat for printing out.
	 */
	private function _prettyPrintDate($startTime, $endTime)
	{
		// Convert to Unix dates.
		$startTime = strtotime($startTime);
		$endTime = strtotime($endTime);
		if (empty($endTime))
		{
			return date("l jS F g:i a.", $startTime);
		}
		else
		{
			if (($startTime + 86400) > $endTime)// it is less than 24 hours long
			{
				return date("l jS F g:i a.", $startTime)." - ".date("g:i a.", $endTime);
			}
			else
			{
				return date("l jS F", $startTime)." - ".date("l jS F", $endTime);
			}
		}
	}
	
	/*
	 * Tidy up the characters so that it can be used in HTML
	 */
	private function _htmlallentities($str){
		$res = '';
		$strlen = strlen($str);
		for($i=0; $i<$strlen; $i++){
			$byte = ord($str[$i]);
			if($byte < 128) // 1-byte char
				$res .= $str[$i];
			elseif($byte < 192); // invalid utf8
			elseif($byte < 224) // 2-byte char
			$res .= '&#'.((63&$byte)*64 + (63&ord($str[++$i]))).';';
			elseif($byte < 240) // 3-byte char
			$res .= '&#'.((15&$byte)*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
			elseif($byte < 248) // 4-byte char
			$res .= '&#'.((15&$byte)*262144 + (63&ord($str[++$i]))*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
		}
		return $res;
	}
	
	/*
	 * Find any hyperlinks and attach <a> tags to them
	 */
	function _txt2link($text)
	{
		// The Regular Expression filter
		// $reg_exUrl = "/(https?|ftps?|file):\/\/([a-z0-9]([a-z0-9_-]*[a-z0-9])?\.)+[a-z]{2,6}\/?([a-z0-9\?\._-~&#=+%]*)?/i";
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		
		// add leading and trailing spaces they serve as URL delimiters in case
		// the URL is at the very beginning or end of $text
		$text = " ".$text." ";
		
		// Check if there is a url in the text
		if(preg_match_all($reg_exUrl, $text, $urls)) 
		{
		   // make the URL hyper links
		   $matches = array_unique($urls[0]);
		   foreach($matches as $match) 
		   {
		      $replacement = "<a href=".$match.">".$match."</a>";
		      $text = str_replace($match, $replacement, $text);
		   }
		   
		   // if URLs in the text, return the text after
		   // removing the leading and trailing spaces
		   return trim($text, " ");
		}
		else 
		{
		   // if no URLs in the text, return the original text after
		   // removing the leading and trailing spaces
		   return trim($text, " ");
		}
	}
}

/*
 * Dumb data object for passing around a list of Facebook events
 */
class Facebook_Events_List
{
	public $events;
	public $sourceUrl;
}

/*
 * Dumb data object for passing Facebook event data around
 */
class Facebook_Event
{
	public $name = "No name found";
	public $pic = "No picture found";
	public $when = "No time (when) found"; // start_time and end_time
	public $descriptionShort = "No short description found";
	public $descriptionLong = "No long description found";
	public $url = "No url found"; // eid;
}

/*
 * You can't pass a parameter to a callback function so the solution is to
 * create an object with the desired state ($name) and the callback method 
 * (taking $facebookEvent as an argument):
 */
class Event_Name_Filter {
	private $nameEnd;

	/*
	 * Constructor to take the string we are looking for on the end of 
	 * the Facebook event name.
	 */
	function __construct($nameEnd) {
		$this->nameEnd = $nameEnd;
	}

	/*
	 * Filter the event name, returning true if the name ends with some string.
	 */
	function filterNameEnd($facebookEvent)
	{
		return $this->_endsWith($facebookEvent->name, $this->nameEnd);
	}
	
	/*
	 * Filter the event name, returning true if the name starts with something.
	 */
	function filterNameStart($facebookEvent)
	{
		return $this->_startsWith($facebookEvent->name, $this->nameEnd);
	}
	
	/*
	 * Curiously there is no "endwith" in PHP so here it is.  That is unless 
	 * I have missed something.  It is case insensitive for convenience.
	 */
	private function _endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0)
		{
			return true;
		}
		return (substr(strtoupper($haystack), -$length) === strtoupper($needle));
	}
	
	/*
	 * Also not startsWith so this is the replacement, it is case insensitive for convenience.
	 */
	private function _startsWith($haystack, $needle)
	{
		return !strncmp(strtoupper($haystack), strtoupper($needle), strlen($needle));
	}
}
