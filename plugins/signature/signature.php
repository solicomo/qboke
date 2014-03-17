<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */

function qb_signature($content) {
	$opts = qb_options('signature');

	if (!isset($opts['enable']) ||
		($opts['enable'] !== true && $opts['enable'] !== 'true') ||
		!isset($opts['signature']) ||
		is_null($opts['signature'])) {
		return $content;;
	}

	if (isset($opts['position']) && $opts['position'] === 'top') {
		return $opts['signature'] . $content;
	}

	return $content . $opts['signature'];
}

add_hook('qb_get_content', 'qb_signature');