<?php
/**
 *	eata WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

// After theme setup hooks
function kalium_child_after_setup_theme() {
	// Load translations for child theme
	load_child_theme_textdomain( 'eata-child', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'kalium_child_after_setup_theme' );

// This will enqueue style.css of child theme
function kalium_child_wp_enqueue_scripts() {
	wp_enqueue_style( 'eata-child', get_stylesheet_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'kalium_child_wp_enqueue_scripts', 100 );


function twentyfifteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Chinese Language Widget', 'twentyfifteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your top header.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title" style="display:none">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyfifteen_widgets_init' );