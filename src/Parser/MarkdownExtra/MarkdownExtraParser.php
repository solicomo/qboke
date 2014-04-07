<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-17
 * */

include_once INC_DIR . '/global.php';
include_once CONVERTORS_DIR . '/convertor.php';

# Get Markdown class
use \Michelf\MarkdownExtra;

class MarkdownExConvertor extends Convertor
{
	public function go(&$content)
	{
		# Pass content through the Markdown praser
		$content = MarkdownExtra::defaultTransform($content);
		return $content;
	}
}

?>
