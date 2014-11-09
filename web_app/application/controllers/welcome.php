<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	private $layout_data = array();
	
	function __construct()
	{
		parent::__construct();
		// The Facebook opening hours are on all pages
		$parameters = array("facebook_page" => FACEBOOK_PAGE);
		$this->load->library('Fb_graph', $parameters);
		$this->layout_data['graph'] = $this->fb_graph->getGraph();
		
		$this->load->library('Blogger_post');
		$this->layout_data['prices_post'] = $this->blogger_post->getPostFromId("5227165301994353023");
		
		// Cache the page output
		$this->output->cache(CACHE_TIME_PAGE);
	}
	
	function index()
	{
		//Flickr carousel
		$this->load->library('Flickr', array('set' => FLICKR_SET_SNIBSTON_HOME));
		$content_data['carousel'] = $this->flickr->getFlickrSet();
		
		//Facebook events
		$this->load->library('Fb_events');
		$content_data['events_list'] = $this->fb_events->getEventsListFilteredOnEnd("Must See");
				
		//Facebook - About and Description
		$this->load->library('Fb_graph');
		$content_data['graph'] = $this->fb_graph->getGraph();
		$this->load->helper('html');
		
		$this->layout_data['title'] = 'Welcome';
		$this->layout_data['content'] = $this->load->view('pages/home', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
	
	function visit()
	{
		$this->_carouselAndBlogger("5651830664980532857", FLICKR_SET_SNIBSTON_VISIT);
	}
	 
	function eat() // and shop
	{
		$this->_carouselAndBlogger("4672060133190970846", FLICKR_SET_SNIBSTON_EAT);
	}
	
	function about()
	{
		$this->_bloggerPage("2906018052522329897");
	}
	
	
	function century()
	{
		//Flickr carousel
		$this->load->library('Flickr', array('set' => FLICKR_SET_SNIBSTON_CENTURY));
		$content_data['carousel'] = $this->flickr->getFlickrSet();
		
		//Facebook events
		$this->load->library('Fb_events');
		$content_data['events_list'] = $this->fb_events->getEventsListFilteredOnEnd("The Century Theatre");
		
		//Facebook graph - for the location
		$content_data['graph'] = $this->layout_data['graph'];

		//JavaScripts to do the more/less on the events text
		$this->layout_data['page_specific_scripts'] = array("/public/js/jquery.shorten.js",
													"/public/js/jquery.shorten.events.js");
		
		//Blogger page
		$this->load->library('Blogger_pages');
		$content_data['blogger_page'] = $this->blogger_pages->getPageFromId("3288054080177950160");
		$this->layout_data['title'] = $content_data['blogger_page']->title;
		$this->layout_data['content'] = $this->load->view('pages/century', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
	
	function events()
	{
		//Flickr carousel
		$this->load->library('Flickr', array('set' => FLICKR_SET_SNIBSTON_WHATS_ON));
		$content_data['carousel'] = $this->flickr->getFlickrSet();
		
		//Facebook events
		$this->load->library('Fb_events');
		$content_data['events_list'] = $this->fb_events->getEventsList();
		
		//Facebook graph - for the location
		$content_data['graph'] = $this->layout_data['graph'];
		
		//JavaScripts to do the more/less on the events text
		$this->layout_data['page_specific_scripts'] = array("/public/js/jquery.shorten.js",
													"/public/js/jquery.shorten.events.js");
				
		$this->layout_data['title'] = "What's On";
		$this->layout_data['content'] = $this->load->view('pages/events', $content_data, true);
		
		$this->load->view('layout', $this->layout_data, false);
	}
	
	function learn()
	{
		$this->_carouselAndBlogger("1996170138319361907", FLICKR_SET_SNIBSTON_LEARN);
	}
	
	function education()
	{
		$this->_bloggerPage("8309791662740567973");
	}

	function sen()
	{
		$this->_bloggerPage("6848581367231874666");
	}
	   
    function sessions()
    {
    	$this->_bloggerPage("6881149957042297276");
    }
		
	function hire()
	{
		$this->_carouselAndBlogger("8202564614514889489", FLICKR_SET_SNIBSTON_HIRE);
	}
	
	function location()
	{	
		//Blogger Page
		$this->load->library('Blogger_pages');
		$content_data['blogger_page'] = $this->blogger_pages->getPageFromId("6810013430353024951");
		//Facebook location
		$this->load->library('Fb_graph');
		$content_data['graph'] = $this->fb_graph->getGraph();
		
		$this->layout_data['title'] = $content_data['blogger_page']->title;
		$this->layout_data['content'] = $this->load->view('pages/location', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
	
	function facilities()
	{
		$this->_bloggerPage("6666756448094921290");
	}
	
	function privacy()
	{
		$this->_bloggerPost("6324518576322272724");
	}
	
	function thanks()
	{
		$this->_bloggerPost("2537041752288875671");
	}
	
	/*
	 * To show any arbitary post use this form of Url
	 *      /welcome/post/2537041752288875671
	 */
	function post()
	{
		$this->_bloggerPost($this->uri->segment(3));
	}
	
	/*
	 * Blogger Page
	 */
	private function _bloggerPage($pageId)
	{
		$this->load->library('Blogger_pages');
		$content_data['blogger_item'] = $this->blogger_pages->getPageFromId($pageId);
		$this->layout_data['title'] = $content_data['blogger_item']->title;
		$this->layout_data['content'] = $this->load->view('pages/blogger', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}

	/*
	 * Carousel and Blogger Page
	 */
	private function _carouselAndBlogger($pageId, $flickrSet)
	{
		$this->load->library('Flickr', array('set' => $flickrSet));
		$content_data['carousel'] = $this->flickr->getFlickrSet();
		$this->load->library('Blogger_pages');
		$content_data['blogger_page'] = $this->blogger_pages->getPageFromId($pageId);
		$this->layout_data['title'] = $content_data['blogger_page']->title;
		$this->layout_data['content'] = $this->load->view('pages/carousel_and_blogger', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
	
	/*
	 * Blogger Post
	 */
	private function _bloggerPost($pageId) 
	{
		$this->load->library('Blogger_post');
		$content_data['blogger_item'] = $this->blogger_post->getPostFromId($pageId);
		$this->layout_data['title'] = $content_data['blogger_item']->title;
		$this->layout_data['content'] = $this->load->view('pages/blogger', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
	
		
	/*
	 * Page not found 404
	 */
	public function error_404()
	{
		$content_data = array();
		$this->output->set_status_header('404');
		$this->layout_data['title'] = "Page Not Found";
		$this->layout_data['content'] = $this->load->view('errors/404', $content_data, true);
		$this->load->view('layout', $this->layout_data, false);
	}
}
?>