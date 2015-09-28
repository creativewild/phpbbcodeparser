<?php

class ImgBbcodeNode implements IBbcodeNode
{
	
	/**
	 * 
	 * @var string
	 */
	private $_target_src = null;
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::isEmpty()
	 */
	public function isEmpty()
	{
		return $this->_target_src === null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return "";
		return '[img]'.$this->_target_src.'[/img]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return "";
		return '<img src="'.urlencode($this->_target_src).'">';
	}
	
}
