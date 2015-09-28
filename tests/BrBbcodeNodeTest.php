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
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse("text before [br] text after");
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
