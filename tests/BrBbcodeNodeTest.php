<?php

class BrBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var BrBbcodeNode
	 */
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new BrBbcodeNode();
	}
	
	protected function tearDown()
	{
		$this->_node = null;
	}
	
	
	public function test_isEmpty()
	{
		$this->assertFalse($this->_node->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals("[br]", $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals("<br>", $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse("[br]");
		$this->assertTrue($this->_node->equals($node));
	}
	
}
