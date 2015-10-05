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
	 * This array is updated at construct time when the configuration is 
	 * given to this class.
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
		'left' => 'LeftBbcodeNode',
		'li' => 'ListItemBbcodeNode',
		'list' => 'ListBbcodeNode',
		'quote' => 'QuoteBbcodeNode',
		'right' => 'RightBbcodeNode',
		's' => 'StrikeBbcodeNode',
		'size' => 'SizeBbcodeNode',
		'spoiler' => 'SpoilerBbcodeNode',
		'table' => 'TableBbcodeNode',
		'td' => 'TableCellBbcodeNode',
		'tr' => 'TableRowBbcodeNode',
		'u' => 'UnderlineBbcodeNode',
		'url' => 'UrlBbcodeNode',
		'youtube' => 'YoutubeBbcodeNode',
	);
	
	/**
	 * The fullstring that is going to be parsed by this parser.
	 * 
	 * @var string
	 */
	protected $_string = null;
	/**
	 * The full length of the above full string. This length is in number
	 * of octets, and dont care about the charset used.
	 * 
	 * @var int
	 */
	protected $_len = null;
	/**
	 * Current position where the parser is working on.
	 * @var int
	 */
	protected $_pos = null;
	/**
	 * Current character on which the parser is working on. It is the character
	 * at the position $this->_pos on the string $this->_string.
	 * @var char
	 */
	protected $_char = null;
	
	/**
	 * The stack of objects that are currently parsed. Like nodes may be nested,
	 * objects are push and popped from this stack each time an opening tag
	 * or an ending tag is encountered by the parser.
	 * 
	 * This stack should never be empty, as it always holds the 
	 * ArticleBbcodeNode which is the base of the stack (and the tree).
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
		
		while($this->_pos < $this->_len)
		{
			$this->parseContent();
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
	
	/**
	 * Parses the content of a node. This method returns each time it
	 * encounters an end group, leading the previous calls of this method to
	 * terminate. If the text is malformed, and more ending tags are than
	 * beginning tags, the result is handled by caller method parse().
	 */
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
	
	/**
	 * Parses the beginning of a group. This dispatches the new created node
	 * among methods to parse its inner contents. 
	 */
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
			if(method_exists($this, $methodname))
			{
				$this->$methodname($node);
			}
			else
			{
				$this->parseDefaultNode($node);
			}
		}
		else
		{
			$element = $this->_stack->top();
			$element->appendText('['.$word);
		}
	}
	
	/**
	 * Parses an end group. This method will pop the top object of the stack,
	 * unless there is no more objects to pop (to keep at least one object in
	 * it). This method disregards the name of the node to be ended, and will 
	 * close the topest node on the stack.
	 */
	protected function parseEndGroup()
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		if($this->_stack->count() > 1)
			$this->_stack->pop();
	}
	
	/**
	 * Parses the text inside a node. This is the default method for parsing
	 * most of the nodes. This method is sufficent for parsing any node that
	 * has no attributes, and which value is only made of its existence.
	 * 
	 * By Default, the nodes that are parsed through this method are :
	 * BoldBbcodeNode,
	 * CenterBbcodeNode,
	 * CodeBbcodeNode,
	 * ItalicBbcodeNode,
	 * LeftBbcodeNode,
	 * ListBbcodeNode,
	 * ListItemBbcodeNode,
	 * RightBbcodeNode,
	 * StrikeBbcodeNode,
	 * TableBbcodeNode,
	 * TableCellBbcodeNode,
	 * TableRowBbcodeNode,
	 * UnderlineBbcodeNode
	 * 
	 * @param IBbcodeNode $node
	 * @return IBbcodeNode
	 */
	protected function parseDefaultNode(IBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		return $node;
	}
	
	
	/**
	 * Parses the text for a br bbcode node.
	 * 
	 * @param BrBbcodeNode $node
	 * @return BrBbcodeNode
	 */
	protected function parseBrBbcodeNode(BrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		return $this->_stack->pop();
	}
	
	/**
	 * Parses the text inside a new color bbcode node.
	 * 
	 * @param ColorBbcodeNode $node
	 * @return ColorBbcodeNode
	 */
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
		return $node;
	}
	
	/**
	 * Parses the text for an hr bbcode node.
	 * 
	 * @param HrBbcodeNode $node
	 * @return HrBbcodeNode
	 */
	protected function parseHrBbcodeNode(HrBbcodeNode $node)
	{
		$pos = strpos($this->_string, ']', $this->_pos - 1);
		if($pos !== false)
			$this->_pos = $pos + 1;
		return $this->_stack->pop();
	}
	
	/**
	 * Parses the text for an image bbcode node.
	 * 
	 * @param ImgBbcodeNode $node
	 * @return ImgBbcodeNode
	 */
	protected function parseImgBbcodeNode(ImgBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$equal_pos = strpos($this->_string, '=', $this->_pos - 1);
			if($equal_pos !== false && $equal_pos < $first_rbracket_pos)
			{
				// form [img=widthxheight]///[/img]
				$dimensions = substr($this->_string,  $equal_pos + 1, $first_rbracket_pos - $equal_pos - 1);
				if(preg_match('#^(\d+)x(\d+)$#', $dimensions, $matches))
				{
					$node->setDimensions((int) $matches[1], (int) $matches[2]);
				}
			}
			// else form [img]///[/img]
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
	
	/**
	 * Parses the text inside a new quote bbcode node.
	 * 
	 * @param QuoteBbcodeNode $node
	 * @return QuoteBbcodeNode
	 */
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
		return $node;
	}
	
	/**
	 * Parses the text inside a new size bbcode node.
	 * 
	 * @param SizeBbcodeNode $node
	 * @return SizeBbcodeNode
	 */
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
	
	/**
	 * Parses the text inside a new spoiler bbcode node.
	 * 
	 * @param SpoilerBbcodeNode $node
	 * @return SpoilerBbcodeNode
	 */
	protected function parseSpoilerBbcodeNode(SpoilerBbcodeNode $node)
	{
		$first_rbracket_pos = strpos($this->_string, ']', $this->_pos - 1);
		if($first_rbracket_pos !== false)
		{
			$equal_pos = strpos($this->_string, '=', $this->_pos - 1);
			if($equal_pos !== false && $equal_pos < $first_rbracket_pos)
			{
				// form [spoiler={title}]{text}[/spoiler]
				$title = substr($this->_string, $equal_pos + 1, $first_rbracket_pos - $equal_pos - 1);
				$node->setTitle($title);
			}
			$this->_pos = $first_rbracket_pos + 1;
			$this->parseContent();
		}
		return $node;
	}
	
	/**
	 * Parses the text inside a new url bbcode node.
	 * 
	 * @param UrlBbcodeNode $node
	 * @return UrlBbcodeNode
	 */
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
	
	/**
	 * Parses the text for a youtube bbcode node.
	 * 
	 * @param YoutubeBbcodeNode $node
	 * @return YoutubeBbcodeNode
	 */
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
	 * Seeks for a known classname within the parsing classmap. This also 
	 * seeks for configurationa-added classnames, and will not find any class
	 * if the tag was denied by configuration.
	 * 
	 * @param string $tagname
	 * @return string the classname for the tag if any, null else.
	 */
	protected function lookupClassname($tagname)
	{
		$tagname = strtolower($tagname);
		if(isset($this->_tagClasses[$tagname]))
			return $this->_tagClasses[$tagname];
		return null;
	}
	
}
