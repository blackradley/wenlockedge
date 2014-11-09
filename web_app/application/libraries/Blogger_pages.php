<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
 * The blog service "Blogger" provides feeds for "Pages" and "Posts".
 * Pages are intended for non-volatile content, for which a publishing
 * date is not relevant.  These "Pages" are used for the non-volatile
 * content of the website.
 * 
 */
require_once('Blogger_item.php');
require_once('Helpers.php');
//require_once('ChromePhp.php');

class Blogger_pages  {
	private $_CI;
	private $_bloggerPages;

	/*
	 * Load the driver for caching and the email helper.
	 */
	function __construct(){
		// We will be caching the data
		$this->_CI =& get_instance();
		$this->_CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc'));
		$this->_CI->load->helper('email');
		$this->_bloggerPages = $this->_getBlogPagesFeed();
	}
	
	/*
	 * Get the Blogger pages feed and cache it.
	 * 
	 * Use cUrl to get the feed for the blog pages.  If there is a cUrl error or
	 * the returned feed is not valid JSON (a 503 errro) use the cached version of 
	 * the feed and warn the webmaster that cUrl could not get the feed.
	 * 
	 * If the cache is older than the last page that got it, get the feed again.
	 * The pages are cached in minutes but the data is cached in seconds (go figure!)
	 * so the CACHE_TIME_PAGE has to be multipled by 60 to see if the cache is
	 * older than the last page that created it.
	 * 
	 * The blog feed contains all the blog pages (not the posts) so it is efficient
	 * to save on making a cUrl call on every page that uses a blog page and to use
	 * the data cache.  Obviously the cache time to live includes the grace period,
	 * so the metadata for the cache is tested directly.
	 */
	private function _getBlogPagesFeed(){
		$cache_name = "blogger_pages.json";
		$cache_metadata = $this->_CI->cache->get_metadata($cache_name);
		// If the cache is older than the last page that got it, get the feed again.
		if (time() > $cache_metadata['mtime'] + (CACHE_TIME_PAGE * 60)) 
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://".BLOGGER_NAME.".blogspot.co.uk/feeds/pages/default?alt=json");
			curl_setopt($ch, CURLOPT_HEADER, FALSE); // remove header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CURL_TIME_OUT);
			
			$feed = curl_exec($ch);
			if(curl_errno($ch)) // get the cached version and send a warning
			{
				$cache_data = json_decode($this->_CI->cache->get($cache_name));
				tell_webmaster("Blogger Pages feed not working.");
			}
			elseif (!$this->_isJson($feed)) // the feed is not valid
			{
				$cache_data = json_decode($this->_CI->cache->get($cache_name));
				tell_webmaster("Blogger Pages feed not valid.");
			}
			else // refresh the cache
			{
				// clean the feed before caching
				$feed = str_replace("\$t", "text", $feed);
				// then cache it, including a period of grace
				$this->_CI->cache->save($cache_name, $feed, CACHE_TIME_DATA);
				$cache_data = json_decode($feed);
			}
			curl_close($ch);
		}
		else // use the cache and avoid another cUrl request
		{
			$cache_data = json_decode($this->_CI->cache->get($cache_name));
		}
		return $cache_data;
	}
	
	/*
	 * Each page on blogger has an Id.  I am not sure if they are universally unique
	 * or just unique within the blog.  It doesn't matter since we can only get the pages
	 * for a single blog at a time
	
		"id":{
			"$t":"tag:blogger.com,1999:blog-7197564171262496043.page-1996170138319361907"
		},
	*/
	function getPageFromId($Id)
	{
		$entries = $this->_bloggerPages->feed->entry;
		foreach($entries as $entry){
			if(isset($entry->id->text) && strpos($entry->id->text, $Id))//There is no StringEndsWith, but this is similar
			{
				$page = new Blogger_item();;
				$page->id = $entry->id->text;
				$page->title = $entry->title->text;
				$page->content = removeTargetBlank($entry->content->text);
				$page->sourceUrl = $this->_findAlternate($entry->link)->href;
				return $page;
			}
		}
		return new Blogger_item; // An empty object
	}
	
	/*
	 * List all the pages for convenience.
	 */
	function getPageList()
	{
		$entries = $this->bloggerPages->feed->entry;
		$pages = array();
		foreach($entries as $entry){
			array_push($pages, array(	"id"		=> $entry->id->text,
								"title"		=> $entry->title->text, 
								"content"	=> $entry->content->text,
								"source"	=> $this->_findAlternate($entry->link)->href));
		}
		return $pages;
	}
	
	/*
	 * Find the "Alternate" URL in the page list.  I have no idea why it is called the "Alternate", 
	 * it looks fine to me.
	 */
	private function _findAlternate($array)
	{
		$item = null;
		foreach($array as $struct) {
			if ("alternate" == $struct->rel) {
				$item = $struct;
				return $item;
			}
		}
		return null;
	}
	
	/*
	 * See if the returned string is json.  If not it is probably an error message.
	 */
	private function _isJson($string) 
	{
		//ChromePhp::log($string);
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
?>
