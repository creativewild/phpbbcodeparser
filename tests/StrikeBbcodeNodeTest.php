<?php

class StrikeBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new StrikeBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode("some striked text"));
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
		$nnode = new StrikeBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[s]some striked text[/s]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<del>some striked text</del>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[s]some striked text[/s]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [s]some striked text[/s] some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode('some text before '));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(' some text after'));
		
		$this->assertEquals($witness, $node);
	}
	
}
