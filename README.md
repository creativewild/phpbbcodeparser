# phpbbcodeparser
A flexible bbcode parser to transform bbcode to html. This parser is made
without any regex stuff.


## Installation

The installation of this libary is made via composer. 
Download `composer.phar` from [their website](https://getcomposer.org/download/).
Then add to your composer.json :

```json
	"require": {
		...
		"anastaszor/phpbbcodeparser": "1.0+"
		...
	}
```

Then run `php composer.phar update` to install this library.
The autoloading of all classes of this library is made through composer's autoloader.

## Test Suite

The run of the test suite is done via phpunit (4.5+).
Download `phpunit.phar` from [their website](https://phpunit.de/index.html).

Then run the `runphpunit` bash script from a console. Both the composer.phar
and the phpunit.phar files should be in the root folder of this project to 
run the test suite, as composer will recompose the vendor directory that phpunit
will use to access to code files.


## Performances

While this bbcode parser is made without calling any regex on tags, the complexity
of parsing a text from this engine is quasi-linear, ~O(n+t), where n is the length
of the text to parse, and t the number of open brackets in that text. That is 
true if the text is well formed, and may not be true if the text is malformed, 
like in cases where a `[` misses its `]` counterpart, due to a large number 
of calls to `strpos`, which is linear.

## Supported Tags

Here is the list:

| Name of tag 		| Syntax											|
|:------------------|:--------------------------------------------------|
| Bold 				| [b]{text}[/b]										|
| Italic 			| [i]{text}[/i]										|
| Underline 		| [u]{text}[/u]										|
| Strike-through 	| [s]{text}[/s]										|
| Font-size 		| [size={number}]{text}[/size]						|
| Font colour 		| [color={colour}]{text}[/color] 					|
| Center text 		| [center]{text}[/center]							|
| Code 				| [code]{text}[/code]								|
| Quote 			| [quote]{text}[/quote]								|
| Named Quote 		| [quote={name}]{text}[/quote]						|
| Link 				| [url]{url}[/url]									|
| Named Link		| [url={url}]{text}[/url]							|
| Image 			| [img]{url}[/img]									|
| List				| [list]\[li]{text}[/li]...[/list]					|
| Tables 			| [table]\[tr]\[td]{text}[/td]...[/tr]...[/table]		|
| Youtube			| [youtube]{video id}[/youtube]						|

This list is based on the list by the [bbcode reference](http://www.bbcode.org/reference.php).
This engine is case-insensitive, meaning that [cEntEr]{...}[/CeNtEr] will work.

! WARNING : One particularity of this parser is that it accepts unterminated 
and incorrect close tag orders. The parser relies only on the `[/` string to 
close a tag, and searches for a `]` if available. In both cases, the deepest
tag will be closed when a `[/` is encountered, without regarding for the 
tag name that is said to be closed. The `IBbcodeNode::toString()` method
replaces the right tags at the right places.

## Security and Usage

Here's how the engine should be used.

1.  First, retrieve the user's bbcode text with some formular.
2.  Use this piece of code:
```php
$text = "<place here your user's input>";
$parser = new PhpBbcodeParser();
$node = $parser->parse($text);

$safebbcode = $node->toString();
```
The `$safebbcode` variable will contain a string which is what the engine
has understand from the inner bbcode. Warning, such string is well bbcode-encoded
but has still to be considered as user-input, and is NOT html-safe, NOR sql safe.

3.  You can use this string to be stored in some persistant storage, e.g. database.

Then, to get the text back into an html page, do the following:

1.  First, retrieve this content from your persistant storage.
2.  Use this piece of code:
```php
$text = "<place here what was stored>";
$parser = new PhpBbcodeParser();
$node = $parser->parse($text);

$safeHtml = $node->toHtml();
```
The `$safeHtml` variable contains now the user's text in safe html encoded
state. 

3.  You can `echo` it directly into your web pages.

! WARNING : For the moment, the engine does not guarantee that the valid bbcode
tree will input a valid html tree. Nested anchors and/or image tags are possible
in bbcode and still invalid in Html.
However, list and table data structures are well build by the parser, and will 
produce valid Html data structures.


## Configuration and Customisation

The PhpBbcodeParser object may accept an array of tags which will be redirected
on creation, and tags that may be forbidden to parse by the engine. The same 
array may be used to define new tags for the engine to support.

The configuration options are as follows:

```php 
$config = array(
	'classes' => array(
		'<tagname>' => '<classname>',	// when the engine will parse given tagname
		...									the classname node will be loaded
	),
	'forbidden' => array(
		'<tagname>',
		'<tagname2>',
	),
);
$parser = new PhpBbcodeParser($config);
```
Note that all <classname> class names for tags must implement the `IBbcodeNode`
interface. Also, using new classnames, the PhpBbcodeParser MUST be extended
in order to be able to parse the new tag for the classname, implementing the
function with signature `public void parse<classname>(<classname> $node);`.

Forbidden nodes will stay as-is as text in the user input, without any formatting.
In case the user chooses to use such tags, they will be in the html as well as
the rest of the text, html-encoded.
