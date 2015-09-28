<?php

class ImgBbcodeNode implements IBbcodeNode
{
	
	/**
	 * 
	 * @var string
	 */
	private $_target_src = null;
	
	/**
	 * Sets target image url with given url.
	 * 
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->_target_src = $url;
	}
	
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
		return '<img src="'.htmlentities($this->_target_src)
			.'" alt="'.htmlentities(basename($this->_target_src)).'">';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof ImgBbcodeNode
			&& !strcmp($this->_target_src, $node->_target_src);
	}
	
}
