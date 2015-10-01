<?php

class ListBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new ListBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode("a first item"));
	}
	
	
	public function test_isEmpty()
	{
		$this->assertFalse($this->_node->isEmpty());
	}
	
	public function test_isEmpty2()
	{
		$nnode = new ListBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[list][li]a first item[/li][/list]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<ul><li>a first item</li></ul>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[list]a first item[/list]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_simpleParsing2()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[list][li]a first item[/li][/list]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [list]a first item[/list] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode('some text before '));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(' and some text after'));
		
		$this->assertEquals($witness, $node);
	}
	
}
