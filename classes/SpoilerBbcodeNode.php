<?php

/**
 * SpoilerBbcodeNode class file.
 *
 * This class represents a spoiler text node. A spoiler text is text that is
 * by default hidden from current visible flow from the user. The user should
 * do manual action to unhide its contents.
 *
 * @author Anastaszor
 */
class SpoilerBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * A random hash to identify this node uniquely among the html dom.
	 * 
	 * @var string
	 */
	private $_random_hash = null;
	
	/**
	 * The title of this spoiler tag, if provided.
	 * 
	 * @var string
	 */
	private $_title = null;
	
	/**
	 * Sets the title of this spoiler tag. The title is the only part which
	 * will still be visible from the spoiler tag.
	 * 
	 * @param string $string
	 */
	public function setTitle($string)
	{
		$this->_title = $string;
	}
	
	/**
	 * Gets the random identifier from this node.
	 * 
	 * @return string
	 */
	public function getRandomHash()
	{
		if($this->_random_hash === null)
			$this->_random_hash = sha1(uniqid(mt_rand(), true).time());
		return $this->_random_hash;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		if($this->_title === null)
			return '[spoiler]'.parent::childrenString().'[/spoiler]';
		return '[spoiler='.$this->_title.']'.parent::childrenString().'[/spoiler]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		$str = '<div class="spoiler"><div onclick="var s = document.getElementById(\''.$this->getRandomHash().
			'\').style; if(s.display == \'none\') { s.display = \'block\'; } else { s.display = \'none\'; }">';
		if($this->_title === null)
			$str .= 'Spoiler:';
		else
			$str .= $this->e($this->_title);
		$str .= '</div><div hidden id="'.$this->getRandomHash().'">'.parent::childrenHtml().'</div></div>';
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof SpoilerBbcodeNode
			&& $node->_title === $this->_title
			&& parent::equals($node);
	}
	
}
