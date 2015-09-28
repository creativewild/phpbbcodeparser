<?php

class UrlBbcodeNode extends AbstractBbcodeNode
{
	
	private $_target_url = null;
	
	public function setUrl($url)
	{
		$this->_target_url = $url;
	}
	
	/**
	 * True if this subtree represents an empty string, false else.
	 *
	 * @return boolean
	 */
	public function isEmpty()
	{
		return $this->_target_url === null && parent::isEmpty();
	}
	
	/**
	 * Gets correctly formed bbcode from this subtree.
	 *
	 * @return string
	*/
	public function toString()
	{
		if($this->isEmpty())
			return "";
		if(count(parent::getChildren()) === 0)
		{
			return '[url]'.$this->_target_url.'[/url]';
		}
		else
		{
			return '[url='.$this->_target_url.']'.parent::toString().'[/url]';
		}
	}
	
	/**
	 * Gets HTML5 compliant string from this subtree.
	 *
	 * @return string
	*/
	public function toHtml()
	{
		if($this->isEmpty())
			return "";
		if(count(parent::getChildren()) === 0)
		{
			return '<a href="'.htmlentities($this->_target_url).'">'.htmlentities($this->_target_url, ENT_QUOTES).'</a>';
		}
		else
		{
			return '<a href="'.htmlentities($this->_target_url).'">'.parent::toHtml().'</a>';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof UrlBbcodeNode
			&& !strcasecmp($this->_target_url, $node->_target_url)
			&& parent::equals($node);
	}
	
}
