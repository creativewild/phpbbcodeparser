<?php

class CodeBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new CodeBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode("some code"));
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
		$nnode = new CodeBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[code]some code[/code]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals('<pre>some code</pre>', $this->_node->toHtml());
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[code]some code[/code]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [code]some code[/code] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode('some text before '));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(' and some text after'));
		
		$this->assertEquals($witness, $node);
	}
	
}
