<?php

class TableBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new TableBbcodeNode();
		$this->_node->addChild(new TextBbcodeNode('some cell text'));
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
		$nnode = new TableBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals(
			'[table][tr][td]some cell text[/td][/tr][/table]', 
			$this->_node->toString()
		);
	}
	
	public function test_toHtml()
	{
		$this->assertEquals(
			'<table><tr><td>some cell text</td></tr></table>',
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[table]some cell text[/table]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_simpleParsing2()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[table][tr]some cell text[/tr][/table]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_simpleParsing3()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[table][tr][td]some cell text[/td][/tr][/table]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_simpleParsing4()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[table][td]some cell text[/td][/table]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [table]some cell text[/table] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode('some text before '));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(' and some text after'));
		
		$this->assertEquals($witness, $node);
	}
	
}
