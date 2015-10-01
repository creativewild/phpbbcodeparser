<?php

class ListBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::addChild()
	 */
	public function addChild(IBbcodeNode $node)
	{
		if($node instanceof ListItemBbcodeNode)
		{
			parent::addChild($node);
		}
		else
		{
			$listitem = new ListItemBbcodeNode();
			$listitem->addChild($node);
			parent::addChild($listitem);
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
		return '[list]'.parent::toString().'[/list]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<ul>'.parent::toHtml().'</ul>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof ListBbcodeNode && parent::equals($node);
	}
	
}
