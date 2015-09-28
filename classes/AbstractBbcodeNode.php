<?php

/**
 * AbstractBbcodeNode class file.
 * 
 * This abstract class is to build the composite pattern by providing regular
 * behavior for every node that may have children.
 * 
 * @author Anastaszor
 */
abstract class AbstractBbcodeNode implements IBbcodeNode
{
	
	/**
	 * All the children of this node.
	 * 
	 * @var IBbcodeNode[]
	 */
	private $_children = array();
	
	/**
	 * Appends a child node to this node.
	 * 
	 * @param IBbcodeNode $node
	 */
	public function addChild(IBbcodeNode $node)
	{
		$this->_children[] = $node;
	}
	
	/**
	 * 
	 * @return IBbcodeNode[]
	 */
	public function getChildren()
	{
		return $this->_children;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::isEmpty()
	 */
	public function isEmpty()
	{
		foreach($this->_children as $child)
		{
			if(!$child->isEmpty())
				return false;
		}
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		$str = '';
		foreach($this->_children as $child)
		{
			$str .= $child->toString();
		}
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		$str = '';
		foreach($this->_children as $child)
		{
			$str .= $child->toHtml();
		}
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		if(!($node instanceof self))
		{
			echo "equals failed: ".get_class($this).' vs '.get_class($node);
			return false;
		}
		$other_children = $node->getChildren();
		if(count($other_children) !== count($this->getChildren()))
		{
			echo "equals failed: ".count($this->getChildren()).' vs '.count($other_children).' children';
			return false;
		}
		foreach($this->getChildren() as $i => $child)
		{
			if(!$child->equals($other_children[$i]))
			{
				echo "equals failed: (".$i.")".get_class($child).' vs '.get_class($other_children[$i]);
				return false;
			}
		}
		return true;
	}
	
}
