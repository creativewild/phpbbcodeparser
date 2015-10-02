<?php

/**
 * ImgBbcodeNode class file.
 * 
 * This class represents an image. This class cannot have children. This class
 * does not test if the given source url is valid, nor if the resource it 
 * points at exists.
 * 
 * @author Anastaszor
 */
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
		return '<img src="'.htmlentities($this->_target_src, ENT_QUOTES)
			.'" alt="'.htmlentities(basename($this->_target_src), ENT_QUOTES).'">';
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
