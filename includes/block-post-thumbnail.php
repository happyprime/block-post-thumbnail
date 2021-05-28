<?php

namespace BlockPostThumbnail;

add_filter( 'has_post_thumbnail', __NAMESPACE__ . '\has_block_thumbnail', 10, 2 );
add_filter( 'default_post_metadata', __NAMESPACE__ . '\filter_block_thumbnail_id', 10, 3 );

/**
 * Filter whether a post has an available post thumbnail image based on the
 * existing _thumbnail_id assignment or the existence of a top level image
 * block in post content.
 *
 * @param bool             $has_thumbnail true if the post has a post thumbnail, otherwise false.
 * @param int|WP_Post|null $post          Post ID or WP_Post object. Default is global `$post`.
 */
function has_block_thumbnail( $has_thumbnail, $post ) {
	if ( $has_thumbnail ) {
		return $has_thumbnail;
	}

	$post = get_post( $post );

	if ( ! $post ) {
		return $has_thumbnail;
	}

	$image_id = get_block_thumbnail_id( $post );

	if ( 0 === $image_id ) {
		return false;
	}

	return true;
}

/**
 * Retrieve the image ID of a top level image block in post content pending
 * the context of the page view.
 *
 * @param int|\WP_Post|null $post Post ID or WP_Post object. Default is global `$post`.
 * @return int The image ID if it is available, otherwise 0.
 */
function get_block_thumbnail_id( $post ) {
	// Not touching this one, ha!
	if ( is_admin() ) {
		return 0;
	}

	// The other way to handle this is to strip the image block that we're using out of
	// post_content and then you should just copy this code and adapt the logic to meet
	// the specific scenario. @todo Or I'll probably add a filter.
	if ( is_single() && get_the_ID() === $post->ID ) {
		return 0;
	}

	$blocks   = parse_blocks( $post->post_content );
	$image_id = 0;

	// We could dig harder, but you could also want other things from custom blocks, so
	// then it's probably better to just copy this code and adapt the logic to your own
	// needs.
	foreach ( $blocks as $block ) {
		if ( 'core/image' === $block['blockName'] ) {
			$image_id = $block['attrs']['id'];
			break;
		}
	}

	return $image_id;
}

/**
 * Provide a "default" value for a post's _thumbnail_id meta key if the circumstances
 * allow for it.
 *
 * @param mixed  $value     The value to return, either a single metadata value or an array
 *                          of values depending on the value of `$single`.
 * @param int    $object_id ID of the object metadata is for.
 * @param string $meta_key  Metadata key.
 * @return int The image ID if it is available, otherwise 0.
 */
function filter_block_thumbnail_id( $value, $object_id, $meta_key ) {
	if ( '_thumbnail_id' !== $meta_key ) {
		return $value;
	}

	$post = get_post( $object_id );

	if ( ! $post ) {
		return $value;
	}

	$image_id = get_block_thumbnail_id( $post );

	return $image_id;
}
