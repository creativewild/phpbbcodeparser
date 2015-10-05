<?php

/**
 * LeftBbcodeNode class file.
 * 
 * This class represents data which should be aligned from left.
 * 
 * @author Anastaszor
 */
class LeftBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[left]'.parent::childrenString().'[/left]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<div style="text-align:left;">'.parent::childrenHtml().'</div>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof LeftBbcodeNode && parent::equals($node);
	}
	
}
