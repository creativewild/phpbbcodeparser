<?php

/**
 * RightBbcodeNode class file.
 *
 * This class represents data which should be aligned from right.
 *
 * @author Anastaszor
 */
class RightBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[right]'.parent::childrenString().'[/right]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<div style="text-align:right;">'.parent::childrenHtml().'</div>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof RightBbcodeNode && parent::equals($node);
	}
	
}
