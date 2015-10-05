<?php

/**
 * UnderlineBbcodeNode class file.
 * 
 * This class represents a text node that is underlined.
 * 
 * @author Anastaszor
 */
class UnderlineBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[u]'.parent::childrenString().'[/u]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<ins>'.parent::childrenHtml().'</ins>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof UnderlineBbcodeNode && parent::equals($node);
	}
	
}
