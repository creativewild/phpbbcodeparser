<?php

class CenterBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new CenterBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode('some centered text'));
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
		$nnode = new CenterBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[center]some centered text[/center]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<div style="text-align:center;">some centered text</div>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[center]some centered text[/center]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [center]some centered text[/center] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
