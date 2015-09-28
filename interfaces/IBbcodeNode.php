<?php

/**
 * IBbcodeNode interface file.
 * 
 * This interface specifies all the actions that every node, or subtree of
 * any bbcode text should be able to do.
 * 
 * @author Anastaszor
 */
interface IBbcodeNode
{
	
	/**
	 * True if this subtree represents an empty string, false else.
	 * 
	 * @return boolean
	 */
	public function isEmpty();
	
	/**
	 * Gets correctly formed bbcode from this subtree.
	 * 
	 * @return string
	 */
	public function toString();
	
	/**
	 * Gets HTML5 compliant string from this subtree.
	 * 
	 * @return string
	 */
	public function toHtml();
	
}
