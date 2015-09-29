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
		'b' => array('class' => 'BoldBbcodeNode', 'autoclosable' => false),
		'br' => array('class' => 'BrBbcodeNode', 'autoclosable' => true),
		'hr' => array('class' => 'HrBbcodeNode', 'autoclosable' => true),
		'img' => array('class' => 'ImgBbcodeNode', 'autoclosable' => false),
		'url' => array('class' => 'UrlBbcodeNode', 'autoclosable' => false),
	);
	
	protected $_string = null;
	protected $_len = null;
	protected $_pos = null;
	protected $_char = null;
	
	/**
	 * 
	 * @var SplStack [AbstractBbcodeNode]
	 */
	protected $_stack = null;
	
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
		$this->_stack->push($base);
		
		$this->parseContent();
		
		if(count($children = $base->getChildren()) === 1)
			return $children[0];
		
		return $base;
	}
	
	/**
	 * Gets current char, then puts the pointer one char forward.
	 */
	protected function getChar()
	{
		$this->_char = $this->_string[$this->_pos++];
	}
	
	/**
	 * 
	 * @param char $char
	 * @return boolean
	 */
	protected function isLetter()
	{
		$ord = ord($this->_char);
		return ($ord >= 65 && $ord <= 90) || ($ord >= 97 && $ord <= 122);
	}
	
	/**
	 * 
	 * @param char $char
	 * @return boolean
	 */
	protected function isNumeric()
	{
		$ord = ord($this->_char);
		return $ord >= 48 && $ord <= 57;
	}
	
	protected function parseContent()
	{
		while($this->_pos < $this->_len)
		{
			$this->getChar();
				
			if($this->_char === '[')
			{
				$this->getChar();
				if($this->_char === '/')
				{
					$this->parseEndGroup();
					return;
				}
				else
				{
					$this->_pos--;
					$this->_char = $this->_string[$this->_pos - 1];
					$this->parseBeginGroup();
				}
			}
			else
			{
				$element = $this->_stack->top();
				$element->appendText($this->_char);
			}
		}
	}
	
	protected function parseBeginGroup()
	{
		$word = "";
		$this->getChar();
		while($this->isLetter())
		{
			$word .= $this->_char;
			$this->getChar();
		}
		$nodeclass = $this->lookupClassname($word);
		if($nodeclass !== null)
		{
			$methodname = 'parse'.$nodeclass;
			$node = new $nodeclass();
			$element = $this->_stack->top();
			$element->addChild($node);
			$this->_stack->push($node);
			$this->$methodname($node);
		}
		else
		{
			$element = $this->_stack->top();
			$element->appendText('['.$word);
		}
	}
	
	protected function parseEndGroup()
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		if($this->_stack->count() > 1)
			$this->_stack->pop();
	}
	
	protected function parseBoldBbcodeNode(BoldBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		else
		{
			// no end bracket found: treat as text
		}
		return $node;
	}
	
	protected function parseBrBbcodeNode(BrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		return $this->_stack->pop();
	}
	
	protected function parseHrBbcodeNode(HrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		return $this->_stack->pop();
	}
	
	protected function parseImgBbcodeNode(ImgBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
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
		return $this->_stack->pop();
	}
	
	protected function parseUrlBbcodeNode(UrlBbcodeNode $node)
	{
		$equals_sign_pos = strpos($this->_string, '=', $this->_pos - 1);
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			if($equals_sign_pos !== false && $equals_sign_pos < $first_rbracket_pos)
			{
				// composite url with [url=///]zzz[/url] syntax
				$url = substr($this->_string, $equals_sign_pos + 1, $first_rbracket_pos - $equals_sign_pos - 1);
				$node->setUrl($url);
				$this->_pos = $first_rbracket_pos +1;
				$this->parseContent();
				return $node;
			}
			else
			{
				$end = stripos($this->_string, '[/url]', $this->_pos);
				// simple [url]///[/url] syntax
				if($end !== null)
				{
					$url = substr($this->_string,
						$first_rbracket_pos + 1,
						$end - $first_rbracket_pos - 1
					);
					$node->setUrl($url);
					$this->_pos = $end + 6;
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
		return $this->_stack->pop();
	}
	
	/**
	 * 
	 * @param string $tagname
	 */
	protected function lookupClassname($tagname)
	{
		$tagname = strtolower($tagname);
		if(isset(self::$_tagClasses[$tagname]))
			return self::$_tagClasses[$tagname]['class'];
		return null;
	}
	
}
