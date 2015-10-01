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
	private $_tagClasses = array(
		'b' => 'BoldBbcodeNode',
		'br' => 'BrBbcodeNode',
		'center' => 'CenterBbcodeNode',
		'code' => 'CodeBbcodeNode',
		'color' => 'ColorBbcodeNode',
		'hr' => 'HrBbcodeNode',
		'i' => 'ItalicBbcodeNode',
		'img' => 'ImgBbcodeNode',
		'li' => 'ListItemBbcodeNode',
		'list' => 'ListBbcodeNode',
		'quote' => 'QuoteBbcodeNode',
		's' => 'StrikeBbcodeNode',
		'size' => 'SizeBbcodeNode',
		'table' => 'TableBbcodeNode',
		'td' => 'TableCellBbcodeNode',
		'tr' => 'TableRowBbcodeNode',
		'u' => 'UnderlineBbcodeNode',
		'url' => 'UrlBbcodeNode',
		'youtube' => 'YoutubeBbcodeNode',
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
	 * Builds a new bbcode parser according to this configuration.
	 * Confirguration options are :
	 * - 'classes' option. Should be an array (string:tagname => string:classname)
	 * 		The classnames should be loadable and should implement IBbcodeNode.
	 * 		The given classnames will override default classname when parsing
	 * 		a specific tag. This can be used to parse specific added tags but 
	 * 		this parser needs to be extended.
	 * - 'forbidden' option. Should be an array (string:tagname)
	 * 		This will forbid the parser to interpret any tag with given tagname.
	 * 		The tags will still be present in the text that's interpreted, but
	 * 		it will be treated as simple text.
	 * 
	 * @param array $config the configuration array
	 */
	public function __construct(array $config = array())
	{
		if(isset($config['classes']))
		{
			$this->_tagClasses = $config['classes'] + $this->_tagClasses;
		}
		if(isset($config['forbidden']))
		{
			foreach($config['forbidden'] as $forbidden_tag)
			{
				unset($this->_tagClasses[$forbidden_tag]);
			}
		}
	}
	
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
		
		if($this->_pos < $this->_len)
		{
			// the parser left some text, because some tags are malformed.
			// append all of this as plain text
			$text = new TextBbcodeNode(substr($this->_string, $this->_pos));
			$base->addChild($text);
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
		if($word === '' && $this->_char === '*')
			$word = 'li';	// to transform [*]...\n to [li]...[/li]
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
	
	protected function parseCenterBbcodeNode(CenterBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
	}
	
	protected function parseCodeBbcodeNode(CodeBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
	}
	
	protected function parseColorBbcodeNode(ColorBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		$equal_pos = strpos($this->_string, '=', $this->_pos - 1);
		if($first_rbracket_pos !== false && $equal_pos !== false
			&& $equal_pos < $first_rbracket_pos
		) {
			$colorval = substr($this->_string, $equal_pos + 1, $first_rbracket_pos - $equal_pos - 1);
			if(preg_match('%^#?[0-9A-Fa-f]{6}$%', $colorval))
			{
				// hexa color
				if(strpos($colorval, '#') === false)
					$colorval = '#'.$colorval;
			}
			$node->setColor($colorval);
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		// else treat as text
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
		if($first_rbracket_pos !== false)
		{
			$end = stripos($this->_string, '[/img]', $this->_pos);
			if($end !== false)
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
	
	protected function parseItalicBbcodeNode(ItalicBbcodeNode $node)
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
	
	protected function parseListBbcodeNode(ListBbcodeNode $node)
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
	
	protected function parseListItemBbcodeNode(ListItemBbcodeNode $node)
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
	
	protected function parseQuoteBbcodeNode(QuoteBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$equal_pos = strpos($this->_string, '=', $this->_pos - 1);
			if($equal_pos !== false && $equal_pos < $first_rbracket_pos)
			{
				// form [quote=author]///[/quote]
				$author = substr($this->_string,  $equal_pos + 1, $first_rbracket_pos - $equal_pos - 1);
				$node->setAuthor($author);
			}
			// else form [quote]///[/quote]
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
	}
	
	protected function parseSizeBbcodeNode(SizeBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		$equal_pos = strpos($this->_string, '=', $this->_pos - 1);
		if($equal_pos !== false && $first_rbracket_pos !== false
			&& $equal_pos < $first_rbracket_pos
		) {
			$sizeval = substr($this->_string, $equal_pos + 1, $first_rbracket_pos - $equal_pos - 1);
			if(preg_match('#^\d+(px|%|em|cm|mm|in|pt|pc)?$#', $sizeval))
			{
				$node->setSize($sizeval);
			}
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		// else treat as text
		return $node;
	}
	
	protected function parseStrikeBbcodeNode(StrikeBbcodeNode $node)
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
	
	protected function parseTableBbcodeNode(TableBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		// else treat as text
		return $node;
	}
	
	protected function parseTableCellBbcodeNode(TableCellBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		// else treat as text
		return $node;
	}
	
	protected function parseTableRowBbcodeNode(TableRowBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		// else treat as text
		return $node;
	}
	
	protected function parseUnderlineBbcodeNode(UnderlineBbcodeNode $node)
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
				if($end !== false)
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
	
	protected function parseYoutubeBbcodeNode(YoutubeBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$end = stripos($this->_string, '[/youtube]', $this->_pos);
			if($end !== false)
			{
				$url = substr($this->_string,
					$first_rbracket_pos + 1,
					$end - $first_rbracket_pos - 1
				);
				if(preg_match('#^[\d\w]{11}$#', $url))
					$node->setVideoTag($url);
				else if(preg_match("#v=([\d\w]{11})#", $url, $matches))
				{
					$node->setVideoTag($matches[1]);
				}
				$this->_pos = $end + 10;
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
	
	/**
	 * 
	 * @param string $tagname
	 */
	protected function lookupClassname($tagname)
	{
		$tagname = strtolower($tagname);
		if(isset($this->_tagClasses[$tagname]))
			return $this->_tagClasses[$tagname];
		return null;
	}
	
}
