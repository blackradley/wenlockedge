<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Send a message to the webmaster. 
 */
function tell_webmaster($message)
{
    $CI =& get_instance();
    $CI->load->library('email');

	$CI->email->initialize(array(
	  'protocol' => 'smtp',
	  'smtp_host' => 'smtp.sendgrid.net',
	  'smtp_user' => SMTP_USER,
	  'smtp_pass' => SMTP_PASS,
	  'smtp_port' => 587,
	  'crlf' => "\r\n",
	  'newline' => "\r\n"
	));

    $CI->email->from('no-reply@snibston.com');
    $CI->email->to(SMTP_DEST);
    $CI->email->subject('Message from www.snibston.com');
    $CI->email->message($message);
    $CI->email->send();

    echo $CI->email->print_debugger();
}
?>