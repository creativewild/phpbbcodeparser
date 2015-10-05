<?php

/**
 * CenterBbcodeNode class file.
 * 
 * This class represents data which should be centered.
 * 
 * @author Anastaszor
 */
class CenterBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[center]'.parent::childrenString().'[/center]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<div style="text-align:center;">'.parent::childrenHtml().'</div>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof CenterBbcodeNode && parent::equals($node);
	}
	
}
