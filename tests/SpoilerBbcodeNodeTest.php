<?php

class SpoilerBbcodeNodeTest extends PhpUnit_Framework_TestCase
{
	
	private $_node = null;
	
	protected function setUp()
	{
		$this->_node = new SpoilerBbcodeNode();
		$this->_node->setTitle("spoiler title");
		$this->_node->addChild(new TextBbcodeNode("spoiled text"));
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
		$nnode = new SpoilerBbcodeNode();
		$this->assertTrue($nnode->isEmpty());
	}
	
	public function test_toString()
	{
		$this->assertEquals(
			'[spoiler=spoiler title]spoiled text[/spoiler]',
			$this->_node->toString()
		);
	}
	
	public function test_toHtml()
	{
		$hash = $this->_node->getRandomHash();
		$this->assertEquals(
			'<div class="spoiler"><div onclick="var s = document.getElementById(\''.$hash
				.'\').style; if(s.display == \'none\') { s.display = \'block\'; } else { s.display = \'none\'; }">'
				.'spoiler title</div><div hidden id="'.$hash.'">spoiled text</div></div>',
			$this->_node->toHtml()
		);
	}
	
	public function test_simpleParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('[spoiler=spoiler title]spoiled text[/spoiler]');
		
		$this->assertEquals($this->_node, $node);
	}
	
	public function test_sandwichParsing()
	{
		$parser = new PhpBbcodeParser();
		$node = $parser->parse('some text before [spoiler=spoiler title]spoiled text[/spoiler] and some text after');
		
		$witness = new ArticleBbcodeNode();
		$witness->addChild(new TextBbcodeNode("some text before "));
		$witness->addChild($this->_node);
		$witness->addChild(new TextBbcodeNode(" and some text after"));
		
		$this->assertEquals($witness, $node);
	}
	
}
