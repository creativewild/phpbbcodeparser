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
			$this->parseNodeInner($base);
		}
		
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
	
	/**
	 * By using this function, we consider that every thing that is from 
	 * current position on the string should be inner of this node. This
	 * function will be called recursively until all the text is parsed.
	 * Non closed tags will be closed at the end of the text until they 
	 * are self-closeable tags.
	 */
	protected function parseNodeInner(AbstractBbcodeNode $node)
	{
		while($this->_pos < $this->_len)
		{
			if($this->_string[$this->_pos] === '[')
			{
				// first char is a bracket, is it an opening tag ?
				$next = strpos($this->_string, ']', $this->_pos);
				if($next === false ||  $next === $this->_pos)
				{
					// the opened brackets is a single bracket not in a tag, let
					// it be, and put the char back in the text
				}
				elseif($next - $this->_pos < 10) // 10: longest tag length
				{
					// the opened brackets is a bracket in a non-tag, let it be
					// and put the char back in the text
				}
				else 
				{
					// tag found.
					$tagval = substr(
						$this->_string, $this->_pos +1, $next - $this->_pos -1
					);
					if($tagval[0] === '/')
					{
						$closing = true;
						$tagval = substr($tagval, 1);
					}
					else
					{
						$closing = false;
					}
					if(preg_match('#^(\w\d)+$#', $tagval))
					{
						// tag is correct
						if($closing)
						{
							$classname = $this->lookupClassname($tagval);
							if($classname === null)
							{
								// unknown tag name, treat it as plain text
							}
							else
							{
								// known tag name. if it is the same class as
								// current node, closes it, else ignore it and
								// threat it as plain text
								if($classname === get_class($node))
								{
									// close the tag at the right place
									$this->_pos = $next +1;
									return;
								}
								else
								{
									$this->_pos = $next +1;
								}
							}
						}
						else
						{
							$classname = $this->lookupClassname($tagval);
							if($classname === null)
							{
								// unknown tag name, treat it as plain text
							}
							else
							{
								// known tag name, generates a new tag and
								// continue parsing at the right place
								$tag = new $classname();
								$this->_pos = $next + 1;
								$this->parseNodeInner($tag);
								$node->addChild($tag);
							}
						}
					}
					else
					{
						// tag is incorrect, treat it as plain text
						$this->_pos = $next +1;
					}
					
				}
				continue;
			}
			// We have text. Use this text until the last bracket.
			$next = strpos($this->_string, '[', $this->_pos);
			if($next === false)
			{
				// the whole text has no brackets and is raw text
				// put it into a new text node
				$node->addChild(new TextBbcodeNode(substr(
					$this->_string, $this->_pos
				)));
				return;
			}
			else 
			{
				// the text is raw until next bracket
				// adds a text node and puts the position pointer forward
				$node->addChild(new TextBbcodeNode(substr(
					$this->_string, $this->_pos, $next - $this->_pos
				)));
				$this->_pos = $next;
			}
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
