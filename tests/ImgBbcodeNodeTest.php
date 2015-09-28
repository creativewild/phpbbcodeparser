<?php

class ImgBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new ImgBbcodeNode();
		$this->_node->setUrl("http://my.super.website.com/path/to/image.png");
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
		$nnode = new ImgBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals(
			"[img]http://my.super.website.com/path/to/image.png[/img]",
			$this->_node->toString()
		);
	}
	
	public function test_toHtml()
	{
		$this->assertEquals(
			'<img src="http://my.super.website.com/path/to/image.png" alt="image.png">',
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$img = $parser->parse("[img]http://my.super.website.com/path/to/image.png[/img]");
		$this->assertEquals($this->_node, $img);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$img = $parser->parse("some text before [img]http://my.super.website.com/path/to/image.png[/img] some text after");
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" some text after"));
		
		$this->assertEquals($witness, $img);
	}
	
}
