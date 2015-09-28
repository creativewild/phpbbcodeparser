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
		$str .= '';
		foreach($this->_children as $child)
		{
			$str .= $child->toHtml();
		}
		return $str;
	}
	
}
