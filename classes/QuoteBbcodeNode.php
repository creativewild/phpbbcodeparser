<?php

/**
 * QuoteBbcodeNode class file.
 * 
 * This class represents a quote made by a user from another user, or an
 * external source. The name of the author may be used in the quote, but is
 * not mandatory.
 * 
 * @author Anastaszor
 */
class QuoteBbcodeNode extends AbstractBbcodeNode
{
	
	/**
	 * The name of the author of the quote.
	 * 
	 * @var string
	 */
	private $_author = null;
	
	/**
	 * Sets the name of the author of the quote.
	 * 
	 * @param string $author
	 */
	public function setAuthor($author)
	{
		$this->_author = trim($author);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toString()
	 */
	public function toString()
	{
		if($this->isEmpty())
			return '';
		if($this->_author === null)
			return '[quote]'.parent::toString().'[/quote]';
		return '[quote='.$this->_author.']'.parent::toString().'[/quote]';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::toHtml()
	 */
	public function toHtml()
	{
		if($this->isEmpty())
			return '';
		if($this->_author === null)
			return '<blockquote>'.parent::toHtml().'</blockquote>';
		return '<blockquote><cite>'.$this->e($this->_author).'</cite>'.parent::toHtml().'</blockquote>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractBbcodeNode::equals()
	 */
	public function equals(IBbcodeNode $node)
	{
		return $node instanceof QuoteBbcodeNode
			&& $this->_author === $node->_author
			&& parent::equals($node);
	}
	
}
