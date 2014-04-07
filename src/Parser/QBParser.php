<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-17
 * */
namespace QBoke\Parser;

/**
 * base parser.
 * do nothing with the content.
 * */
class QBParser
{
	public function go(&$content)
	{
		return $content;
	}
}

