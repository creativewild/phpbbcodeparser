<?php

class YoutubeBbcodeNode implements IBbcodeNode
{
	
	/**
	 * The youtube video tag.
	 * 
	 * @var string
	 */
	private $_video_tag = null;
	
	/**
	 * Sets the video tag id. This should not be the full video url.
	 * 
	 * @param string $tagname
	 */
	public function setVideoTag($tagname)
	{
		$this->_video_tag = $tagname;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::isEmpty()
	 */
	public function isEmpty()
	{
		return $this->_video_tag === null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		return '[youtube]'.$this->_video_tag.'[/youtube]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		return '<iframe allowfullscreen frameborder="0" height="315" width="420" src="https://www.youtube.com/embed/'.
			htmlentities($this->_video_tag, ENT_QUOTES).'"></iframe>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof YoutubeBbcodeNode 
			&& $this->_video_tag === $node->_video_tag;
	}
	
}
