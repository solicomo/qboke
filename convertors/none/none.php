<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */

include_once INC_DIR . '/vars.php';
include_once CONVERTORS_DIR . '/convertor.php';

/**
 * none convertor.
 * do nothing with the content.
 * */
class NoneConvertor extends Convertor
{
	public function go(&$content)
	{
		return $content;
	}
}

?>