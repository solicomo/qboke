<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-04-17
 * */

/**
 * base convertor.
 * do nothing with the content.
 * */
class Convertor
{
	public function &do(&$content)
	{
		return $content;
	}
}
?>
