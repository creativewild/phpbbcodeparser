<?php

/**
 * ColorBbcodeNode class file.
 * 
 * This class represents a node where the text will have another color, given
 * by a text representing its id, or by its 6-hexa-digit code.
 * 
 * @author Anastaszor
 */
class ColorBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * The color to give the text.
	 * 
	 * @var string
	 */
	private $_color = null;
	
	/**
	 * Adds the color value to the node. Accepted values are plain text, which
	 * will be interpreted by the navigators, and #RGB values with the #.
	 * 
	 * @param string $value
	 */
	public function setColor($value)
	{
		$this->_color = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		if($this->_color === null)
			return parent::childrenString();
		return '[color='.$this->_color.']'.parent::childrenString().'[/color]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		if($this->_color === null)
			return parent::childrenHtml();
		return '<span style="color:'.$this->e($this->_color).';">'.parent::childrenHtml().'</span>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof ColorBbcodeNode
			&& $node->_color === $this->_color
			&& parent::equals($node);
	}
	
}
