<?php

/**
 * TableRowBbcodeNode class file.
 * 
 * This class represents a table row. This class can only contain table cells,
 * to be compliant with the html table structure. This class can only be used
 * into table nodes, to be compliant with the html table structure.
 * 
 * @author Anastaszor
 */
class TableRowBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::addChild()
	 */
	public function addChild(IBbcodeNode $node)
	{
		if($node instanceof TableCellBbcodeNode)
		{
			parent::addChild($node);
		}
		else
		{
			$tablecell = new TableCellBbcodeNode();
			$tablecell->addChild($node);
			parent::addChild($tablecell);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::appendText()
	 */
	public function appendText($string)
	{
		if(($cnt = count($chd = parent::getChildren())) > 0)
		{
			$child = $chd[$cnt - 1];
			$child->appendText($string);
		}
		else
		{
			$this->addChild(new TextBbcodeNode($string));
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[tr]'.parent::toString().'[/tr]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<tr>'.parent::toHtml().'</tr>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof TableRowBbcodeNode && parent::equals($node);
	}
	
}
