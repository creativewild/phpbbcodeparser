<?php

/**
 * TableBbcodeNode class file.
 * 
 * This class represents a table node. This class can only contains table rows,
 * to be compliant with the html table structure.
 * 
 * @author Anastaszor
 */
class TableBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::addChild()
	 */
	public function addChild(IBbcodeNode $node)
	{
		if($node instanceof TableRowBbcodeNode)
		{
			parent::addChild($node);
		}
		else
		{
			$tablerow = new TableRowBbcodeNode();
			$tablerow->addChild($node);
			parent::addChild($tablerow);
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
		return '[table]'.parent::toString().'[/table]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<table>'.parent::toHtml().'</table>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof TableBbcodeNode && parent::equals($node);
	}
	
}
