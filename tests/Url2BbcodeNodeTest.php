<?php

class Url2BbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	/**
	 *
	 * @var UrlBbcodeNode
	 */
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new UrlBbcodeNode();
		$this->_node->setUrl("http://my.super.website.com/path/to/resource.htm");
		$this->_node->addChild(new TextBbcodeNode("this is the link"));
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
		$nnode = new UrlBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals(
			'[url=http://my.super.website.com/path/to/resource.htm]this is the link[/url]',
			$this->_node->toString()
		);
	}
	
	public function test_toHtml()
	{
		$this->assertEquals(
			'<a href="http://my.super.website.com/path/to/resource.htm">this is the link</a>',
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$img = $parser->parse("[url=http://my.super.website.com/path/to/resource.htm]this is the link[/url]");
	
		$this->assertEquals($this->_node, $img);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$img = $parser->parse("some text before [url=http://my.super.website.com/path/to/resource.htm]this is the link[/url] some text after");
	
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" some text after"));
	
		$this->assertEquals($witness, $img);
	}
	
}
