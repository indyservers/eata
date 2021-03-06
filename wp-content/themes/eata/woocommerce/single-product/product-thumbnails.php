<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 		https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

// start: modified by Arlind
$shop_single_product_images_layout = get_data( 'shop_single_product_images_layout' );

// Supported only on default (carousel type)
if ( 'default' != $shop_single_product_images_layout ) {
	return;
}

// Prepend featured image to thumbnails list
$skip_featured_image = apply_filters( 'kalium_woocommerce_skip_featured_image', false );

if ( has_post_thumbnail() && ! empty( $attachment_ids ) && ! $skip_featured_image ) {
	// Remove Featured Image from the list
	if ( ( $key = array_search( get_post_thumbnail_id(), $attachment_ids ) ) !== false ) {
		unset( $attachment_ids[ $key ] );
	}
	
	array_unshift( $attachment_ids, get_post_thumbnail_id() );
}
// end: modified by Arlind

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<div class="thumbnails <?php 
		echo 'columns-' . $columns; 
		when_match( $skip_featured_image, 'featured-image-skipped' ); // Modified by Arlind Nushi
	?>"><?php

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop === 0 || $loop % $columns === 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns === 0 ) {
				$classes[] = 'last';
			}

			$image_class = implode( ' ', $classes );
			$props	   = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}

			echo apply_filters(
				'woocommerce_single_product_image_thumbnail_html',
				sprintf(
					'<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $image_class ),
					esc_attr( $props['caption'] ),
					wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
				),
				$attachment_id,
				$post->ID,
				esc_attr( $image_class )
			);

			$loop++;
		}

	?></div>
	<?php
}
