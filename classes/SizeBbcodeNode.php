<?php

/**
 * SizeBbcodeNode class file.
 *
 * This class represents a text node which size is modified. The size of the
 * text can be smaller or larger, indifferently.
 *
 * @author Anastaszor
 */
class SizeBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * The value of the size, unit given.
	 * @var string
	 */
	private $_size = null;
	
	/**
	 * Sets the size of this node. Accepted values are alone integer values,
	 * or integer strings with the unit, which may be one among :
	 * em, px, %, cm, mm, in, pt, pc.
	 * Note that we are in a screen context, recommanded units are only 
	 * em, px, and %.
	 * If no unit are given, the value will be interpreted as percentage of
	 * the current font size.
	 * 
	 * Note : 1in = 2.54cm = 25.4mm = 72pt = 6pc 
	 * 
	 * @param string $value
	 * @see http://www.w3.org/Style/Examples/007/units.en.html
	 */
	public function setSize($value)
	{
		if(((string) intval($value)) === $value)
			$value .= '%';
		$this->_size = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		if($this->_size === null)
			return parent::childrenString();
		return '[size='.$this->_size.']'.parent::childrenString().'[/size]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		if($this->_size === null)
			return parent::childrenHtml();
		return '<span style="font-size:'.$this->e($this->_size).';">'.parent::childrenHtml().'</span>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof SizeBbcodeNode 
			&& $node->_size === $this->_size
			&& parent::equals($node);
	}
	
}
