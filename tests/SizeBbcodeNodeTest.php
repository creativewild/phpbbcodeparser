<?php

class SizeBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new SizeBbcodeNode();
		$this->_node->setSize("14px");
		$this->_node->addChild(new TextBbcodeNode("some resized text"));
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
		$nnode = new SizeBbcodeNode();
		$nnode->setSize("14px");
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[size=14px]some resized text[/size]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<span style="font-size:14px;">some resized text</span>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[size=14px]some resized text[/size]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [size=14px]some resized text[/size] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
