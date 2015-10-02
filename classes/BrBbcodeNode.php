<?php

/**
 * BrBbcodeNode class file.
 * 
 * This class represents a forced line breaks. This class cannot have children.
 * 
 * @author Anastaszor
 */
class BrBbcodeNode implements IBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::isEmpty()
	 */
	public function isEmpty()
	{
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		return "[br]";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		return "<br>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof BrBbcodeNode;
	}
	
}
