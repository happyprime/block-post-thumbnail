<?php
/*
Plugin Name: Block Post Thumbnail
Plugin URI: https://
Description: Use an image block from a post in place of a featured image.
Version: 0.0.1
Author: jeremyfelt
Author URI: https://jeremyfelt.com
License: GPLv23 or later
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// This plugin, like WordPress, requires PHP 5.6 and higher.
if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
	add_action( 'admin_notices', 'block_post_thumbnail_admin_notice' );
	/**
	 * Display an admin notice if PHP is not 5.6.
	 */
	function block_post_thumbnail_admin_notice() {
		echo '<div class=\"error\"><p>';
		echo __( 'The Block Post Thumbnail WordPress plugin requires PHP 5.6 to function properly. Please upgrade PHP or deactivate the plugin.', 'block-post-thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</p></div>';
	}

	return;
}

require_once __DIR__ . '/includes/block-post-thumbnail.php';
