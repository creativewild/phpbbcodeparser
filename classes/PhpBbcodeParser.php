<?php

/**
 * PhpBbcodeParser class file.
 * 
 * This class is made to build the bbcode tree that represents given rich text.
 * 
 * @author Anastaszor
 */
class PhpBbcodeParser implements IBbcodeParser
{
	
	/**
	 * All the conversions from tag names to node class names.
	 * @var string
	 */
	private static $_tagClasses = array(
		'br' => array('class' => 'BrBbcodeNode', 'autoclosable' => true),
		'hr' => array('class' => 'HrBbcodeNode', 'autoclosable' => true),
		'img' => array('class' => 'ImgBbcodeNode', 'autoclosable' => false),
		'url' => array('class' => 'UrlBbcodeNode', 'autoclosable' => false),
	);
	
	private $_string = null;
	private $_len = null;
	private $_pos = null;
	
	private $_stack = null;
	
	/**
	 * (non-PHPdoc)
	 * @see IBbcodeParser::parse()
	 */
	public function parse($string)
	{
		$this->_string = $string;
		$this->_len = strlen($string);
		$this->_pos = 0;
		$this->_stack = new SplStack();
		
		$base = new ArticleBbcodeNode();
		
		if($this->_len > 1)
		{
			$this->parseContent($base);
		}
		
		if(count($children = $base->getChildren()) === 1)
			return $children[0];
		
		return $base;
	}
	
	/**
	 * Gets current char, then puts the pointer one char forward.
	 */
	protected function getChar()
	{
		return $this->_string[$this->_pos++];
	}
	
	/**
	 * 
	 * @param char $char
	 * @return boolean
	 */
	public function isLetter($char)
	{
		$ord = ord($char);
		return ($ord >= 65 && $ord <= 90)
			|| ($ord >= 97 && $ord <= 122);
	}
	
	/**
	 * 
	 * @param char $char
	 * @return boolean
	 */
	public function isNumeric($char)
	{
		$ord = ord($char);
		return $ord >= 48 && $ord <= 57;
	}
	
	protected function parseContent(AbstractBbcodeNode $node)
	{
		while($this->_pos < $this->_len)
		{
			$char = $this->getChar();
			if($char === '[')
			{
				$word = "";
				while($this->isLetter($innerchar = $this->getChar()))
				{
					$word .= $innerchar;
				}
				$this->_pos--;	// rewind last character, we pushed it too far
				$newnode = $this->parseDispatch($word);
				if($newnode !== null)
				{
					$node->addChild($newnode);
					continue;
				}
			}
			// treat this piece of text as raw text, until next "["
			$next = strpos($this->_string, '[', $this->_pos);
			if($next === false)
			{
				// the whole text has no brackets and is raw text
				// put it into a new text node
				$node->addChild(new TextBbcodeNode(substr(
					$this->_string, $this->_pos - 1
				)));
				return;
			}
			else
			{
				// the text is raw until next bracket
				// adds a text node and puts the position pointer forward
				$node->addChild(new TextBbcodeNode(substr(
					$this->_string, $this->_pos - 1, $next - $this->_pos + 1
				)));
				$this->_pos = $next;
			}
		}
	}
	
	protected function parseDispatch($nodename)
	{
		$nodeclass = $this->lookupClassname($nodename);
		if($nodeclass === null)
			return null;
		$methodname = 'parse'.$nodeclass;
		return $this->$methodname(new $nodeclass());
	}
	
	protected function parseBrBbcodeNode(BrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos);
		if($pos === false)
			$this->_pos = $this->_len;
		else 
			$this->_pos = $pos + 1;
		return $node;
	}
	
	protected function parseHrBbcodeNode(HrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos);
		if($pos === false)
			$this->_pos = $this->_len;
		else 
			$this->_pos = $pos + 1;
		return $node;
	}
	
	protected function parseImgBbcodeNode(ImgBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos);
		if($first_rbracket_pos !== null)
		{
			$end = stripos($this->_string, '[/img]', $this->_pos);
			if($end !== null)
			{
				$url = substr($this->_string, 
					$first_rbracket_pos + 1, 
					$end - $first_rbracket_pos - 1
				);
				$node->setUrl($url);
				$this->_pos = $end + 6;
				return $node;
			}
			else
			{
				// no end tag found: treat as text
			}
		}
		else
		{
			// no end bracket found: treat as text
		}
	}
	
	protected function parseUrlBbcodeNode(UrlBbcodeNode $node)
	{
		$equals_sign_pos = strpos($this->_string, '=', $this->_pos);
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos);
		if($first_rbracket_pos !== false)
		{
			$end = stripos($this->_string, '[/url]', $this->_pos);
			if($equals_sign_pos !== false && $equals_sign_pos < $first_rbracket_pos)
			{
				// composite url with [url=///]zzz[/url] syntax
				$url = substr($this->_string, $equals_sign_pos + 1, $first_rbracket_pos - $equals_sign_pos - 1);
				$node->setUrl($url);
				$node->addChild(new TextBbcodeNode(substr($this->_string, 
					$first_rbracket_pos + 1, $end - $first_rbracket_pos - 1
				)));
				$this->_pos = $end + 6;
				return $node;
			}
			else
			{
				// simple [url]///[/url] syntax
				if($end !== null)
				{
					$url = substr($this->_string,
						$first_rbracket_pos + 1,
						$end - $first_rbracket_pos - 1
					);
					$node->setUrl($url);
					$this->_pos = $end + 6;
					return $node;
				}
				else
				{
					// no end tag found: treat as text
				}
			}
		}
		else
		{
			// no end bracket found: treat as text
		}
	}
	
	/**
	 * 
	 * @param string $tagname
	 */
	public function lookupClassname($tagname)
	{
		$tagname = strtolower($tagname);
		if(isset(self::$_tagClasses[$tagname]))
			return self::$_tagClasses[$tagname]['class'];
		return null;
	}
	
}
