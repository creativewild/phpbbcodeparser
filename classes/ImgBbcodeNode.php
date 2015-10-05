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
	 * 
	 * @var int
	 */
	private $_width = null;
	/**
	 * 
	 * @var int
	 */
	private $_height = null;
	
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
	 * Sets the dimensions, in pixels, at which the image should be displayed.
	 * @param int $width
	 * @param int $height
	 */
	public function setDimensions($width, $height)
	{
		$this->_width = $width;
		$this->_height = $height;
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
	 * @see IBbcodeNode::appendText()
	 */
	public function appendText($string)
	{
		// does nothing
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return "";
		if($this->_height === null || $this->_width === null)
			return '[img]'.$this->_target_src.'[/img]';
		return '[img='.$this->_width.'x'.$this->_height.']'.$this->_target_src.'[/img]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return "";
		$str = '<img src="'.htmlentities($this->_target_src, ENT_QUOTES)
			.'" alt="'.htmlentities(basename($this->_target_src), ENT_QUOTES).'"';
		if($this->_height === null || $this->_width === null)
			return $str .'>';
		else
			return $str . ' style="width:'.htmlentities($this->_width, ENT_QUOTES)
				.'px; height:'.htmlentities($this->_height, ENT_QUOTES).'px;">';
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
