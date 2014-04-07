<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
namespace \QBoke\Parser\Donothing;

use \QBoke\Parser\QBParser;

/**
 * donothing parser.
 * do nothing with the content.
 * */
class DonothingParser extends QBParser
{
	public function go(&$content)
	{
		return $content;
	}
}
