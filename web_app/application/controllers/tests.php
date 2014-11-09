<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Tests extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->helper('html');
		$this->unit->set_test_items(array('test_name', 'result', 'file', 'line'));
	}
	
	function index()
	{
		$this->all();
	}
	
	function all() {
		$this->testAddAnchorTags();
	}
	
	/*
	 * 
	 */
	function testAddAnchorTags()
	{
		$testArray = array (
				'link http://www.snibston.org.uk/ it is' => 'link <a href="http://www.snibston.org.uk/">http://www.snibston.org.uk/</a> it is',
				'google http://goo.gl/4sLvum' => 'google <a href="http://goo.gl/4sLvum">http://goo.gl/4sLvum</a>',
				'youtube http://www.youtube.com/watch?v=_X32OrY0wJQ' => 'youtube <a href="http://www.youtube.com/watch?v=_X32OrY0wJQ">http://www.youtube.com/watch?v=_X32OrY0wJQ</a>',
		);
		foreach ($testArray as $testString => $expected) {
			$actual = addAnchorTags($testString);
			echo $this->unit->run($actual, $expected, 'Add anchors to '. $testString);
		}
	}
}
?>