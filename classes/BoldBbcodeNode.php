<?php

/**
 * BoldBbcodeNode class file.
 * 
 * This class represents a text node that is bolded. (larger types fonts than
 * normal fonts).
 * 
 * @author Anastaszor
 */
class BoldBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[b]'.parent::childrenString().'[/b]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<strong>'.parent::childrenHtml().'</strong>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof BoldBbcodeNode && parent::equals($node);
	}
	
}
