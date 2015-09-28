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
	 * Gets correctly formed bbcode from this tree.
	 * 
	 * @return string
	 */
	public function toString();
	
	/**
	 * Gets HTML5 compliant string from this tree.
	 * 
	 * @return string
	 */
	public function toHtml();
	
}
