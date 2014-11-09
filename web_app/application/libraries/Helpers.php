<?php 

/*
 * Utility/Helper functions.
 */


/*
 * Remove the target="_blank" for the home url, but not for any others.
 */
function removeTargetBlank($content)
{
	$host = $_SERVER["SERVER_NAME"];
	$regexString ='#(<a href="http://' . $host . '[a-z\/]*")[\s]target="_blank"#i';
	return preg_replace($regexString, '$1', $content);
}

?>
