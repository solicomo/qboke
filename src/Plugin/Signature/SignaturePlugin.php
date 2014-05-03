<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */
namespace QBoke\Plugin\Signature;

use QBoke\Common\QBGlobal;

class SignaturePlugin
{
	public function init()
	{
		$g = QBGlobal::getInstance();

		$g->add_hook('qb_get_content', array(&$this, 'signature'));
	}

	public function signature($content)
	{
		$g = QBGlobal::getInstance();

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
