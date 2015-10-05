<?php

class Img2BbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new ImgBbcodeNode();
		$this->_node->setDimensions(640, 480);
		$this->_node->setUrl("http://my.super.url.com/path/to/resource.jpg");
	}
	
	protected function tearDown()
	{
		$this->_node = null;
	}
	
	
	public function test_isEmpty()
	{
		$this->assertFalse($this->_node->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals(
			'[img=640x480]http://my.super.url.com/path/to/resource.jpg[/img]', 
			$this->_node->toString()
		);
	}
	
	public function test_toHtml()
	{
		$this->assertEquals(
			'<img src="http://my.super.url.com/path/to/resource.jpg" alt="resource.jpg" style="width:640px; height:480px;">',
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[img=640x480]http://my.super.url.com/path/to/resource.jpg[/img]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [img=640x480]http://my.super.url.com/path/to/resource.jpg[/img] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
