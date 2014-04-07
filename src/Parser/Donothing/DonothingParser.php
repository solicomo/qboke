<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
use \QBoke\Parser\QBParser;

namespace \QBoke\Parser\Donothing;

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
