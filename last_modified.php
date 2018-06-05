<?php
/*
* Plugin Name: Last-Modified and 304 Not-Modified plugin
* Plugin URI: https://github.com/rrubashkin/WordPress-plugin-for-Last-Modified-304-Not-Modified-headers
* Description: A simple wordpress plugin to add 'last modified' and '304 Not Modified' headers to your site
* Version: 1.0.0
* Author: rrubashkin
* Author URI: https://github.com/rrubashkin/
* License: GPL v3
* Domain Path: The domain path let WordPress know where to find the translations. More information can be found in the Domain Path section of the How to Internationalize your Plugin page.
*/

add_action( 'template_redirect', 'last_modified_304_headers' );


function last_modified_304_headers() {

	$LastModified = null;

	$IfModifiedSince = isset( $_ENV['HTTP_IF_MODIFIED_SINCE'] ) ? $_ENV['HTTP_IF_MODIFIED_SINCE'] : null;
	$IfModifiedSince = isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : $IfModifiedSince;

	$IfModifiedSince = strtotime( substr( $IfModifiedSince, 5 ) );


	if ( is_singular() ) {
		global $post;
		$LastModified = get_the_modified_time('U', $post );

	} elseif ( is_archive() ) {
		global $wp_query;
		foreach ( $wp_query->posts as $the_post ) {
			$LastModified = max( $LastModified, get_the_modified_time( 'U', $the_post ) );
		}

	} else {
		$LastModified = time();
	}

	if ( $IfModifiedSince && $IfModifiedSince >= $LastModified ) {
		header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );
		exit;
	} else {
		header( 'Last-Modified: ' . date( 'D, d M Y H:i:s', $LastModified ) );
	}
}
