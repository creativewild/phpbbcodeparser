<?php

class ColorBbcodeNode extends AbstractBbcodeNode
{
	
	private $_color = null;
	
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
			return parent::toString();
		return '[color='.$this->_color.']'.parent::toString().'[/color]';
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
			return parent::toHtml();
		return '<span style="color:'.$this->e($this->_color).';">'.parent::toHtml().'</span>';
	}
	
}
