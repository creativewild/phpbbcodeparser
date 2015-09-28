<?php

class TextBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var TextBbcodeNode
	 */
	private $_node = null;
	
	
	protected function setUp()
	{
		$this->_node = new TextBbcodeNode("a new text [!] < >");
	}
	
	protected function tearDown()
	{
		$this->_node = null;
	}
	
	
	public function test_isEmpty()
	{
		$this->assertFalse($this->_node->isEmpty());
	}
	
	public function test_isEmpty2()
	{
		$nnode = new TextBbcodeNode("");
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals("a new text [!] < >", $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals("a new text [!] &lt; &gt;", $this->_node->toHtml());
	}
	
}
