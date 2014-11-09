<?php 

require_once('Helpers.php'); // Remember to add the libraries directory to the include path for the project.
		
/*
 * Test the helper functions
 */
class Helpers_Test extends PHPUnit_Framework_TestCase
{
   
    /*
     * 
     */
    public function testRemoveTargetBlank()
    {
    	// The host name is supplied by phpunit.xml for the purposes of the test.
    	$testArray = array (
    			'<a href="http://www.example.com" target="_blank">click here</a>' => '<a href="http://www.example.com">click here</a>',
    			'<a href="http://www.dummy.com" target="_blank">click here</a>' => '<a href="http://www.dummy.com" target="_blank">click here</a>',
    	);
    	foreach ($testArray as $testString => $expected) {
    		$actual = removeTargetBlank($testString);
    		$this->assertEquals($expected, $actual);
    	}   	
    }
    
}
?>