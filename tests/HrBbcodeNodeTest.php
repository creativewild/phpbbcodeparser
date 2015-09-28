<?php

class HrBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var HrBbcodeNode
	 */
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new HrBbcodeNode();
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
		$this->assertEquals("[hr]", $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals("<hr>", $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse("[hr]");
		$this->assertTrue($this->_node->equals($node));
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse("text before [hr] text after");
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" text after"));
		
		$this->assertTrue($witness->equals($node));
	}
	
}
