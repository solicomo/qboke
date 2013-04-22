<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-17
 * */

include_once("../convertor.php");
include_once("php_markdown/Michelf/Markdown.php");
include_once("php-markdown/Michelf/MarkdownExtra.php");

# Get Markdown class
use \Michelf\MarkdownExtra;

class MarkdownExConvertor extends Convertor
{
	public function &do(&$content)
	{
		# Pass content through the Markdown praser
		$content = MarkdownExtra::defaultTransform($content);
		return $content;
	}
}
?>
