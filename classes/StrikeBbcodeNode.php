<?php

/**
 * StrikeBbcodeNode class file.
 * 
 * This node represents some text which is striked through, i.e. a line is
 * drawn in the middle of the letters.
 * 
 * @author Anastaszor
 */
class StrikeBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[s]'.parent::toString().'[/s]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<del>'.parent::toString().'</del>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof StrikeBbcodeNode && parent::equals($node);
	}
	
}
