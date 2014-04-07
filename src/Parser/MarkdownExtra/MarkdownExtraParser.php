<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-17
 * */
# Get Markdown class
use \Michelf\MarkdownExtra;
use \QBoke\Parser\QBParser;

namespace \QBoke\Parser\MarkdownExtra;

class MarkdownExtraParser extends QBParser
{
	public function go(&$content)
	{
		# Pass content through the Markdown praser
		$content = MarkdownExtra::defaultTransform($content);
		return $content;
	}
}

