<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */
namespace QBoke\Plugin\Signature;

class SignaturePlugin
{
	public function signature($content)
	{
		global $g;

		$opts = qb_options('signature');

		if (!isset($opts['enable']) ||
			($opts['enable'] !== true && $opts['enable'] !== 'true')
			) {
			return $content;
		}

		if (!isset($opts['signature']) || is_null($opts['signature'])) {
			return $content;;
		}

		if (!$g->response->is_post()) {
			return $content;
		}

		if (isset($opts['position']) && $opts['position'] === 'top') {
			return $opts['signature'] . $content;
		}

		return $content . $opts['signature'];
	}
}
