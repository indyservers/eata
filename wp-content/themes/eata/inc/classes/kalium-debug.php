<?php
/**
 *	Kalium WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

class Kalium {
	
	public static $theme_directory;
	
	public static $theme_directory_uri;
	
	public function __construct() {
		self::$theme_directory = get_template_directory() . '/';
		self::$theme_directory_uri = get_template_directory_uri() . '/';		
	}
	
	// Locate file in theme directory
	public function locateFile( $relative_path = '' ) {
		return Kalium::$theme_directory . ltrim( $relative_path, '/' );
	}
	
	// Locate URL in theme directory
	public function locateFileUrl( $relative_path = '' ) {
		return Kalium::$theme_directory_uri . ltrim( $relative_path, '/' );
	}
	
	// Assets URL
	public function assetsUrl( $relative_path = '' ) {
		return $this->locateFileUrl( 'assets/' . ltrim( $relative_path, '/' ) );
	}
	
	// Assets PATH
	public function assetsPath( $relative_path = '' ) {
		return $this->locateFile( 'assets/' . ltrim( $relative_path, '/' ) );
	}
}


// Kalium
$kalium = new Kalium();

function kalium() {
	global $kalium;
	return $kalium;
}

// Get Theme Options data
$theme_options_data = get_theme_mods();

function get_data( $var = null, $default = '' ) {
	global $theme_options_data;
	
	if ( $var == null ) {
		return apply_filters( 'get_theme_options', $theme_options_data );
	}

	if ( isset( $theme_options_data[ $var ] ) ) {
		$value = $theme_options_data[ $var ];
		
		// Treat numeric values as "number"
		if ( is_numeric( $value ) ) {
			if ( is_int( $value ) ) {
				$value = intval( $value );
		 	} elseif ( is_float( $value ) ) {
				$value = floatval( $value );
			} elseif ( is_double( $value ) ) {
				$value = doubleval( $value );
			}								
		}
		 
		return apply_filters( "get_data_{$var}", $value );
	}

	return apply_filters( "get_data_{$var}", $default );
}


function is_shop_supported() {
	return is_array( get_option( 'active_plugins' ) ) && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

function kalium_get_loading_spinners() {
	return array();
}