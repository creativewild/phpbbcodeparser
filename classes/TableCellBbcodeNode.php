<?php

/**
 * TableCellBbcodeNode class file.
 * 
 * This class represents a table cell. This class can only be used into a
 * table row node, to be compliant with the html structure.
 * 
 * @author Anastaszor
 */
class TableCellBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[td]'.parent::childrenString().'[/td]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<td>'.parent::childrenString().'</td>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof TableCellBbcodeNode && parent::equals($node);
	}
	
}
