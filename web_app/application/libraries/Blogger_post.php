<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * The blog service "Blogger" provides feeds for "Pages" and "Posts".
 * Pages are intended for non-volatile content, for which a publishing
 * date is not relevant. 
 */

require_once('Blogger_item.php');
require_once('Helpers.php');
//require_once('ChromePhp.php');

class Blogger_post  {
	private $_CI;
	private $_bloggerPost;
	
	/*
	 * Load the driver for caching and the email helper.
	 */
	function __construct(){
		// We will be caching the data
		$this->_CI =& get_instance();
		$this->_CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'apc'));
		$this->_CI->load->helper('email');
	}
	
	/*
	 * Posts are not complete in the post list so get them directly from the postId,
	 * rather than from the post list on blogger. 
	 */
	private function _getBlogPostData($postId){
		$cache_name = "blogger_post_".$postId.".json";
		$cache_data = null;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.blogger.com/feeds/".BLOGGER_ID."/posts/default/".$postId."?alt=json");
		curl_setopt($ch, CURLOPT_HEADER, FALSE); // remove header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, CURL_TIME_OUT);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // ignore invalid certificate error which appeared on Azure.
		
		$feed = curl_exec($ch);
		if(curl_errno($ch)) // get the cached version and send a warning
		{
			$cache_data = json_decode($this->_CI->cache->get($cache_name));
			tell_webmaster("Blogger Post feed ".$postId." not working.");
		}
		elseif (!$this->_isJson($feed)) // the feed is not valid
		{
			$cache_data = json_decode($this->_CI->cache->get($cache_name));
			tell_webmaster("Blogger Post feed ".$postId." not valid. Got this instead (".$feed.")");
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
		return $cache_data;
	}
	
	/*
	 * Get the blog post
	 */
	function getPostFromId($postId)
	{
		$entry = $this->_getBlogPostData($postId)->entry;
		$post = new Blogger_item();
		$post->id = $entry->id->text;
		$post->title = $entry->title->text;
		$post->content = removeTargetBlank($entry->content->text);
		$post->sourceUrl = $this->_findAlternate($entry->link)->href;
		return $post;
	}
	
	/*
	 * Find the "Alternate" URL in the page list.  I have no idea why it is called the "Alternate",
	 * it looks fine to me.
	 */
	private function _findAlternate($array){
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
	private function _isJson($string) {
		//ChromePhp::log($string);
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
?>