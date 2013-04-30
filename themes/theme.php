<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

class Theme {
	public function render() {
		if ( is_post() ) {
			require( 'post.php' );
		} else if ( is_index() ) {
			require( 'index.php' );
		} else if ( is_tag() ) {
			require( 'tag.php' );
		}
	}
}
?>