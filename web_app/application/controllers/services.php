<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Services extends CI_Controller {

	/*
	 * Provide a site map.
	 */
    function sitemap()
    {
    	$this->output->cache(43200); // 30 days
    	
    	$data['base_url'] = $this->config->base_url()."welcome";
        $data['urls'] =  array("/visit", "/eat", "/about", "/facilities", 
        		"/century",
        		"/events", 
        		"/learn", "/education", "/sessions", "/sen",
        		"/hire",
        		"/location"	
        );
        header("Content-Type: text/xml;charset=iso-8859-1");
        $this->load->view("sitemap", $data);
    }   
    
    /*
     * Twitter will switch off the 1.0 API on 7 May 2013.  All requests will have to be 
     * authenticated after that.  Since we don't want to put a username etc... in the 
     * Twitter JavaScript this is a proxy in front of the twitter feed which does the 
     * authentication.  Essentially this is https://github.com/StanScates/Tweet.js-Mod 
     * with a few modifications to get it to work on Codeigniter.
     */
    function twitterfeed()
    {
    	$this->load->library('Twitter');
    	$this->twitter->fetch();
    }

    /*
     * Send a test message to check the email works.
     */
    function send()
    {
        $this->load->helper('email_helper');
        echo tell_webmaster("Test message sent at ".date("Y-m-d H:i:s").".");
    }
    
    /*
     * While the application is on AppFog we can't look at the cached files, so this
     * simple browser gives access.  Since the content is the same as the website it
     * it unsecured.  The source comes from... 
     *    http://oscardias.com/development/php/codeigniter/file-browser-codeigniter/
     * Which in turn is based on...
     *    https://github.com/EllisLab/CodeIgniter/wiki/simple-file-browser
     */
    function cache()
    {
    	$segment_array = $this->uri->segment_array();
    
    	// first and second segments are the controller and method
    	$controller = array_shift( $segment_array );
    	$method = array_shift( $segment_array );
    
    	// absolute path using additional segments
    	$path_in_url = '';
    	foreach ( $segment_array as $segment ) $path_in_url.= $segment.'/';
    	$absolute_path = getcwd().'/application/cache/'.$path_in_url;
    	$absolute_path = rtrim( $absolute_path ,'/' );
    
    	// check if it is a path or file
    	if ( is_dir( $absolute_path ))
    	{
    		// link generation helper
    		$this->load->helper('url');
    
    		$dirs = array();
    		$files = array();
    		// fetching directory
    		if ( $handle = @opendir( $absolute_path ))
    		{
    			while ( false !== ($file = readdir( $handle )))
    			{
    				if (( $file != "." AND $file != ".." ))
    				{
    					if ( is_dir( $absolute_path.'/'.$file ))
    					{
    						$dirs[]['name'] = $file;
    					}
    					else
    					{
    						$files[]['name'] = $file;
    					}
    				}
    			}
    			closedir( $handle );
    			sort( $dirs );
    			sort( $files );
    
    		}
    		// parent folder
    		// ensure it exists and is the first in array
    		if ( $path_in_url != '' )
    			array_unshift ( $dirs, array( 'name' => '..' ));
    
    		// view data
    		$data = array(
    				'controller' => $controller,
    				'method' => $method,
    				'virtual_root' => getcwd(),
    				'path_in_url' => $path_in_url,
    				'dirs' => $dirs,
    				'files' => $files,
    		);
    		$this->load->view( 'browser', $data );
    	}
    	else
    	{
    		// is it a file?
    		if ( is_file($absolute_path) )
    		{
    			// open it
    			header ('Cache-Control: no-store, no-cache, must-revalidate');
    			header ('Cache-Control: pre-check=0, post-check=0, max-age=0');
    			header ('Pragma: no-cache');
    
    			$text_types = array(
    					'php', 'css', 'js', 'html', 'txt', 'htaccess', 'xml'
    			);
    			$path_parts = pathinfo($absolute_path);
    			// download necessary ?
    			if( isset($path_parts['extension']) && in_array( $path_parts['extension'], $text_types) ) {
    				header('Content-Type: text/plain');
    			} else {
    				header('Content-Type: application/x-download');
    				header('Content-Length: ' . filesize( $absolute_path ));
    				header('Content-Disposition: attachment; filename=' . basename( $absolute_path ));
    			}
    
    			@readfile( $absolute_path );
    		}
    		else
    		{
    			show_404();
    		}
    	}
    }
}