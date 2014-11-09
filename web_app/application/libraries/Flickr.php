<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require_once('ChromePhp.php');

/*
 * Flickr Class - The feed does not reqire API Key like I originally thought,
 * (thanks to Matt P for pointing that out).
 */

class Flickr 
{
	private $set;
	private $CI;
	private $flickrFeed;

	/*
	 * Load the driver for caching and the email helper.
	 */
	function __construct($parameters)
	{
		$this->set = $parameters['set'];
		// We will be caching the data
		$this->CI =& get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc'));
		$this->CI->load->helper('email');
		$this->flickrFeed = $this->_getFlickrFeed();
	}
	
	/*
	 * Get the Flickr feed for a set and cache it.
	 * 
	 * Use cUrl to get the feed for a set from Flickr.  If there is a cUrl error use
	 * the cached version of the feed and warn the webmaster that cUrl could not get
	 * the feed.
	 * 
	 * The page outputs should be cached for CACHE_TIME so the CACHE_TIME_MULTIPLIER
	 * means that there is a period of grace while the data cache is used to update
	 * the page output cache so that the any cUrl problem can be fixed.
	 */
	private function _getFlickrFeed()
	{
		$cache_name = "flickr_".$this->set.".json";
		$cache_data = null;
		$ch = curl_init();
		ChromePhp::log("http://api.flickr.com/services/feeds/photoset.gne?format=json&nojsoncallback=1&set=$this->set&nsid=".FLICKR_USER);
		curl_setopt($ch, CURLOPT_URL, "http://api.flickr.com/services/feeds/photoset.gne?format=json&nojsoncallback=1&set=$this->set&nsid=".FLICKR_USER);
		curl_setopt($ch, CURLOPT_HEADER, FALSE); // remove header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CURL_TIME_OUT);
		
		$feed = curl_exec($ch);
		if(curl_errno($ch)) // get the cached version and send a warning
		{
			$cache_data = json_decode($this->CI->cache->get($cache_name));
			tell_webmaster("Flickr feed ".$this->set." not working.");
		}
		else // refresh the cache
		{
			// clean the feed, there should be no escape characters
			$feed = str_replace("\'", "'", $feed); 
			// then cache it, including a period of grace
			$this->CI->cache->save($cache_name, $feed, CACHE_TIME_DATA);
			$cache_data = json_decode($feed);
		}
		curl_close($ch);
		return $cache_data;
	}
	
	/*
	 * Get Flickr Set
	 */
	function getFlickrSet()
	{
		$flickrSet = new Flickr_Set();
		$flickrSet->set = $this->_getImages();
		$flickrSet->sourceUrl = $this->_getSourceUrl();
		return $flickrSet;
	}

	/*
	 * Callback function to sort by date_taken so that the images can be reordered.
	 */
	private function _sortByDateTaken( $a, $b ) 
	{
		return strtotime($a["dateTaken"]) - strtotime($b["dateTaken"]);
	}
	
	/*
	 *  Return the gallery list as array
	 */
	private function _getImages()
	{
		$items = $this->flickrFeed->items;
		$gallery = array();
		foreach($items as $k => $v){
			$content = $v->description;
			$content = preg_replace("/\<p\>(.*)posted a photo:\<\/p\>/is", "", $content);
			$content = strip_tags($content);
		
			$gallery[] = array(	"title"		=> $v->title,
								"content"	=> $content,
								"image"		=> str_replace("_m", "", $v->media->m),
								"sourceUrl"	=> $v->link,
								"dateTaken" => $v->date_taken
			);
		}
		usort($gallery, array($this, "_sortByDateTaken")); 
		return $gallery;
	}
	
	/*
	 * Return the URL of the source set.
	 */
	private function _getSourceUrl()
	{
		return "http://www.flickr.com/photos/".FLICKR_USER."/sets/".$this->set;
	}
}

/*
 * Dumb data object for passing around 
 */
class Flickr_Set 
{
	public $set= "No set found";
	public $sourceUrl = "No source Url found";
}
