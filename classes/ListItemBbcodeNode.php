<?php

/**
 * ListItemBbcodeNode class file.
 * 
 * This class represents a list item. This class can only be used into a list
 * node, in order to be compliant with the html list structure.
 * 
 * @author Anastaszor
 */
class ListItemBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[li]'.parent::toString().'[/li]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<li>'.parent::toHtml().'</li>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof ListItemBbcodeNode && parent::equals($node);
	}
	
}
