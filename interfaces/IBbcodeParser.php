<?php

/**
 * IBbcodeParser interface file.
 * 
 * This interface specifies the tasks that any parser intended to parse
 * bbcode should be able to do.
 * 
 * @author Anastaszor
 */
interface IBbcodeParser
{
	
	/**
	 * Parses the text as bbcode, then returns the bbcode resulting tree.
	 * 
	 * @param string $string
	 * @return IBbcodeNode
	 */
	public function parse($string);
	
}
