<?php

class LeftBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new LeftBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode("some ltr text"));
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
		$nnode = new LeftBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[left]some ltr text[/left]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<div style="text-align:left;">some ltr text</div>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[left]some ltr text[/left]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [left]some ltr text[/left] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode('some text before '));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(' and some text after'));
		
		$this->assertEquals($witness, $node);
	}
	
}
