<?php

/**
 * TextBbcodeNode class file.
 * 
 * This class is made to be the smallest unit into the bbcode tree, a node
 * that only represents the text that's inside, without any formatting.
 * This class is the only class that cannot have children.
 * 
 * @author Anastaszor
 */
class TextBbcodeNode implements IBbcodeNode
{
	
	/**
	 * The raw text content in this node.
	 * @var string
	 */
	private $_raw_text = null;
	
	/**
	 * TextBbcodeNode constructor.
	 * 
	 * This takes the raw string that will be treated as bbcode raw text.
	 * 
	 * @param string $string
	 */
	public function __construct($string)
	{
		$this->_raw_text = $string;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::isEmpty()
	 */
	public function isEmpty()
	{
		return $this->_raw_text === null || $this->_raw_text === "";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		return $this->_raw_text;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		return htmlentities($this->_raw_text, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof TextBbcodeNode 
			&& !strcmp($this->_raw_text, $node->_raw_text);
	}
	
}
