<?php

/**
 * ItalicBbcodeNode class file.
 * 
 * This class represents a text node that is italicized. (thiner types fonts 
 * with ~15-30° angle forward from top of the letter).
 * 
 * @author Anastaszor
 */
class ItalicBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[i]'.parent::toString().'[/i]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<em>'.parent::toHtml().'</em>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof ItalicBbcodeNode && parent::equals($node);
	}
	
}
