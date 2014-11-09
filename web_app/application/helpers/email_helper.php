<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Send a message to the webmaster.  This only works if the from address
 * matched the address of the AppFog user.
 */
function tell_webmaster($message)
{
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->from('no-reply@snibston.com');
	$CI->email->to('joe_collins@blackradley.com');
	$CI->email->subject('Message from www.snibston.com');
	$CI->email->message($message);
	$CI->email->send();
}
?>