<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
namespace QBoke\SCM\Git;

use QBoke\Common\QBGlobal;

$g = QBGlobal::getInstance();
$g->set_scm('git', __NAMESPACE__ . '\GitScm');

