<?php

class HrBbcodeNode implements IBbcodeNode
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
		return "[hr]";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		return "<hr>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof HrBbcodeNode;
	}
	
}
