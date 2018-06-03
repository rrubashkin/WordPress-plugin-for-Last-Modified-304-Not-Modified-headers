<?php
/**
 * Created by PhpStorm.
 * User: @RRubashkin
 * Date: 03.06.2018
 * Time: 18:37
 * A simple wordpress plugin to add 'last modified' and '304 Not Modified' headers
 */

add_action( 'template_redirect', 'last_modified_304_headers' );


function last_modified_304_headers() {

	$LastModified = null;

	$IfModifiedSince = isset( $_ENV['HTTP_IF_MODIFIED_SINCE'] ) ? $_ENV['HTTP_IF_MODIFIED_SINCE'] : null;
	$IfModifiedSince = isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : $IfModifiedSince;

	$IfModifiedSince = strtotime( substr( $IfModifiedSince, 5 ) );


	if ( is_singular() ) {
		global $post;
		$LastModified = get_the_modified_time( $post );

	} elseif ( is_archive() ) {
		global $wp_query;
		foreach ( $wp_query->posts as $the_post ) {
			$LastModified = max( $LastModified, get_the_modified_time( $the_post ) );
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
