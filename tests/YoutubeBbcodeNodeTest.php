<?php

class YoutubeBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new YoutubeBbcodeNode();
		$this->_node->setVideoTag("UkWd0_zv3fQ");
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
		$nnode = new YoutubeBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals('[youtube]UkWd0_zv3fQ[/youtube]', $this->_node->toString());
	}
	
	public function test_toHtml()
	{
		$this->assertEquals(
			'<iframe allowfullscreen frameborder="0" height="315" width="420" src="https://www.youtube.com/embed/UkWd0_zv3fQ"></iframe>', 
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[youtube]UkWd0_zv3fQ[/youtube]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [youtube]UkWd0_zv3fQ[/youtube] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
