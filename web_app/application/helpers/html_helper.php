<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * HTML Helpers
 */

/*
 * Add anchor tags to an http link in a text string
 */
function addAnchorTags($text)
{
	$regexString = '@((http|https)://([\w-.]+)+(:\d+)?(/([\w/_\-.]*(\?\S+)?)?)?)@';
	$newText = preg_replace($regexString, '<a href="$1">$1</a>', $text);
	return $newText;
}

?>