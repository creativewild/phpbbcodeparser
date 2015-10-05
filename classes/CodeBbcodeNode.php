<?php

/**
 * CodeBbcodeNode class file.
 * 
 * This class represents some text where the spaces between characters matters.
 * Such nodes are generally implemented with monospaced fonts.
 * 
 * @author Anastaszor
 */
class CodeBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[code]'.parent::childrenString().'[/code]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<pre>'.parent::childrenString().'</pre>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof CodeBbcodeNode && parent::equals($node);
	}
	
}
