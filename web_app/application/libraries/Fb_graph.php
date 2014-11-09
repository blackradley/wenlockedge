<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

include 'ChromePhp.php'; // For use with Chrome Logger (http://craig.is/writing/chrome-logger)
require_once('Helpers.php');

/*
 * Get the Facebook graph for a Facebook page
 */
class Fb_graph 
{
	private $CI;
	private $facebookGraph;
	private $days = array("mon"	=> "Monday",
				"tue"	=> "Tuesday",
				"wed"	=> "Wednesday",
				"thu"	=> "Thursday",
				"fri"	=> "Friday",
				"sat"	=> "Saturday",
				"sun"	=> "Sunday");

	function __construct()
	{
		// We will be caching the data
		$this->CI =& get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc'));
		$this->CI->load->helper('email');
		$this->CI->config->load('wrekin', false, true);
		
		$this->facebookGraph = $this->_getFacebookGraph();
	}
	
	/*
	 * Get the Facebook Graph feed.  The Facebook Graph feed is cached
	 * so the it can be used in case of a cUrl error, but also so it 
	 * can be used by all the other pages.  If it were only used on one 
	 * or two pages the page output caching would be sufficient.  But since 
	 * it is used on other pages we can used the cached data rather than 
	 * making a cUrl request, if the data is still fresh enough.
	 * 
	 * Generally this caching is a bit of a drag to debug so I wonder
	 * about it's utility.  However, it seems to work so I am going to 
	 * leave it in.
	 */
	private function _getFacebookGraph(){
		$cache_name = "facebook_graph.json";
		$cache_data = null;
		$cache_metadata = $this->CI->cache->get_metadata($cache_name);
		if (time() > $cache_metadata['mtime'] + (CACHE_TIME_PAGE * 60)) // the cache is old get the feed again.
		{
			log_message('debug', 'Fb_graph making cUrl request.');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://graph.facebook.com/".FACEBOOK_PAGE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE); // remove header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$feed = curl_exec($ch);
			if(curl_errno($ch)) // get the cached version and send a warning
			{
				log_message('debug', 'Fb_graph cUrl error using data cache.');
				$cache_data = json_decode($this->CI->cache->get($cache_name));
				tell_webmaster("Facebook Graph feed not working.");
			}
			else // refresh the cache including a period of grace
			{
				log_message('debug', 'Fb_graph caching data from cUrl.');
				$this->CI->cache->save($cache_name, $feed, CACHE_TIME_DATA);
				$cache_data = json_decode($feed);
			}
			curl_close($ch);
		}
		else // use the cache and avoid another cUrl request
		{
			log_message('debug', 'Fb_graph using data cache to save cUrl calls.');
			$cache_data = json_decode($this->CI->cache->get($cache_name));
		}
		return $cache_data;
	}
	
	/*
	 * Return opening hours as array
	 */
	function getOpeningHours()
	{
		// Check if hours are in the Facebook Graph
		if (isset( $this->facebookGraph->hours))
		{
			$hours = (array) $this->facebookGraph->hours;
		}

		$ranges = array();
		$rtn = array();
	
		foreach ($this->days as $d => $dn) 
		{
			$oc = $this->_getOpeningContent($d);
				
			// if days are identical (e.g. monday and tuesday are the same) extend the range
			// otherwise create a new one
			if (count($ranges) > 0 && $ranges[count($ranges)-1]['oc'] == $oc) {
				$ranges[count($ranges)-1]['end'] = $d;
			} else {
				$ranges[] = array("start"=> $d, "end"=> $d, "oc"=> $oc);
			}
		}
		foreach ($ranges as $range) 
		{
			// single or range
			if ($range['start'] == $range['end']) $key = ucfirst($this->days[$range['end']]);
			else $key = ucfirst($this->days[$range['start']]) . " - " . ucfirst($this->days[$range['end']]);
				
			$rtn[$key] = $range['oc'];
		}
		return $rtn;
	}
	
	/*
	 * 
	 */
	private function _getOpeningContent($d)
	{
		// Check if hours are in the Facebook Graph
		if (isset( $this->facebookGraph->hours))
		{
			$hours = (array) $this->facebookGraph->hours;
		}
	
		if(isset($hours[$d . "_2_open"]) || isset($hours[$d . "_2_close"]))
		{
			throw_error("Module does not support multiple opening hours on the same day");
		}
	
		if(isset($hours[$d . "_1_open"]) && isset($hours[$d . "_1_close"]))
		{
			$open = $hours[$d . "_1_open"];
			$close = $hours[$d . "_1_close"];
			$oc = $open . " - " . $close;
		}
		else
		{
			$closed_text = $this->CI->config->item('closed_text');
			//ChromePhp::log($closed_text);
			$oc = empty($closed_text)? "Closed" : $closed_text;

		}
		return $oc;
	}
	
	/*
	 * 
	 */
	function getOpeningHoursToday()
	{
		//$hours = (array) $this->facebookGraph->hours;
		$d = strtolower(date("D"));
		return $this->_getOpeningContent($d);
	}
	
	/*
	 * 
	 */
	function getGraph()
	{
		$facebookGraph = new Facebook_Graph();
		$facebookGraph->sourceUrl = $this->_getSourceUrl();
		$facebookGraph->name = $this->facebookGraph->name;
		$facebookGraph->openingHoursToday = $this->getOpeningHoursToday();
		$facebookGraph->openingHours = $this->getOpeningHours();
		if(isset($this->facebookGraph->public_transit))
		{
			$facebookGraph->publicTransport = $this->facebookGraph->public_transit;
		}
		if(isset($this->facebookGraph->about))
		{
			$facebookGraph->about = $this->facebookGraph->about;
		}
		if(isset($this->facebookGraph->description))
		{
			$facebookGraph->description = $this->facebookGraph->description;
		}
		if(isset($this->facebookGraph->phone))
		{
			$facebookGraph->phone = $this->facebookGraph->phone;
		}
		$location = new Location();
		$location->address = $this->_getAddress();
		$location->latitude = $this->facebookGraph->location->latitude;
		$location->longitude = $this->facebookGraph->location->longitude;
		$facebookGraph->location = $location;
		return $facebookGraph;
	}
	
	/*
	 *
	 */
	private function _getSourceUrl()
	{
		return "https://www.facebook.com/".FACEBOOK_PAGE."/info";
	}
	
	/*
	 * Return a string with the address elements separated by <br/>.
	 * If the elements of the location object has not been set then
	 * set them to ""
	 */
	private function _getAddress()
	{
		$location = $this->facebookGraph->location;

		$street = isset($location->street) ? $location->street : "";
		$city = isset($location->city) ? $location->city : "";
		$state = isset($location->state) ? $location->state : "";
		$country = isset($location->country) ? $location->country : "";
		$zip = isset($location->zip) ? $location->zip : "";

		$addressArray = array($street, $city, $state, $country, $zip);
		$addressArray = array_filter($addressArray); // Filter out the blanks
		return implode('<br/>', $addressArray);
	}
}

/*
 * Dumb data objects for passing around
 */
class Facebook_Graph 
{
	public $sourceUrl = "No source Url found";
	public $name = "No name found";
	public $openingHoursToday = "No opening hours for today found";
	public $openingHours = "No opening hours found";
	public $publicTransport = "Sorry no information about public transport links found";
	public $about = "No about text found";
	public $description = "No description text found";
	public $phone = "No phone number found";
	public $location;	
}

class Location 
{
	public $address = "No address found";
	public $latitude = "No latitude found";
	public $longitude = "No longitude found";
}
