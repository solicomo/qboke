<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-17
 * */

include_once INC_DIR . '/var.php';
include_once CONVERTORS_DIR . '/convertor.php';
include_once CONVERTORS_DIR . '/markdownex/php-markdown/Michelf/Markdown.php';
include_once CONVERTORS_DIR . '/markdownex/php-markdown/Michelf/MarkdownExtra.php';

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

set_convertor('markdownex', new MarkdownExConvertor);

?>
