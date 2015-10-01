<?php

class QuoteBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new QuoteBbcodeNode();
		$this->_node->setAuthor("myself");
		$this->_node->addChild(new TextBbcodeNode("some quoted text"));
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
		$nnode = new QuoteBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[quote=myself]some quoted text[/quote]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<blockquote><cite>myself</cite>some quoted text</blockquote>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[quote=myself]some quoted text[/quote]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [quote=myself]some quoted text[/quote] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
