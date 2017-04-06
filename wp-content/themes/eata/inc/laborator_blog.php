<?php
/**
 *	Kalium WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

// External Post Redirect
function kalium_external_post_format_redirect() {
	global $post;
	
	if ( is_single() && 'link' == get_post_format() && apply_filters( 'kalium_blog_external_link_redirect', true ) ) {
		$urls = wp_extract_urls( $post->post_content );
		
		if ( $urls ) {
			wp_redirect( current( $urls ) );
			exit;
		}
	}
}

add_action( 'template_redirect', 'kalium_external_post_format_redirect' );