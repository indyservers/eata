<?php
/**
 *	Kalium Main Theme Class
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

if ( class_exists( 'Kalium' ) ) {
	return;
}

final class Kalium extends Kalium_Base {
	
	/**
	 *	Main instance of Kalium theme
	 */
	private static $_instance;
	
	/**
	 *	Autoload classes array
	 */
	private static $_autoload;
	
	/**
	 *	Theme version
	 */
	private static $version = '2.0.4';
	
	/**
	 *	Theme execution start time
	 */
	protected static $start_time;
	
	/**
	 *	Theme Directory
	 */
	public static $theme_directory;
	
	/**
	 *	Theme Directory URL
	 */
	public static $theme_directory_uri;
	
	/**
	 *	Loaded Class Instances
	 */
	public $class_instances;
	
	/**
	 *	Theme Constructor executed only once per request
	 */
	public function __construct() {
		if ( self::$_instance ) {
			_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '4.7' );
		}
	}

	/**
	 * You cannot clone this class
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '4.7' );
	}

	/**
	 * You cannot unserialize instances of this class
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '4.7' );
	}
	
	/**
	 *	Load Class Instance
	 */
	public function __get( $name ) {
		$instance = self::$_instance;
		$sub_instance_id = "kalium_{$name}";
		
		return $instance->class_instances->$sub_instance_id;
	}
	 
	/**
	 *	Create Instance of this class
	 */
	public static function instance() {
		global $kalium;
		
		if ( ! self::$_instance ) {
			self::$_instance = new self();
			self::$_autoload = require 'load-classes.php';
			
			$kalium = self::$_instance;
			
			// Theme Directory
			self::$theme_directory = get_template_directory() . '/';
			self::$theme_directory_uri = get_template_directory_uri() . '/';
			
			// Start time of Kalium execution
			self::$start_time = microtime( true );
			
			// After Setup Theme
			add_action( 'after_setup_theme', array( self::$_instance, 'afterSetupTheme' ) );
			
			// Autoload Classes
			self::$_instance->class_instances = (object) array();
			
			foreach ( self::$_autoload as $class_name => $class_path ) {
				if ( ! class_exists( $class_name ) ) {
					require_once $class_path;
					$class_name_lower = strtolower( $class_name );
					self::$_instance->class_instances->$class_name_lower = new $class_name( self::$_instance );
					self::$_instance->class_instances->$class_name_lower->admin_page = is_admin() && isset( $_GET['page'] ) ? $_GET['page'] : null;
				}
			}
			
			// Add actions for loaded class instances
			self::$_instance->addActionsToClassInstances();
			
			// Setup Laborator admin menu
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				self::$_instance->initAdminPages();
			}
		}
		
		return self::$_instance;
	}
	
	/**
	 *	Execute Actions for Class Instances
	 */
	public function addActionsToClassInstances() {
		$frontend_actions = array( 'init', 'wp', 'template_redirect', 'wp_enqueue_scripts', 'wp_footer' );
		$admin_actions    = array( 'init', 'admin_menu', 'admin_init' );
		$assigned_actions = is_admin() ? $admin_actions : $frontend_actions;
		
		foreach ( self::$_instance->class_instances as $instance_name => & $class_instance ) {
			foreach ( $admin_actions as $action_name ) {
				if ( method_exists( $class_instance, $action_name ) ) {
					$priority_property = "{$action_name}_priority";
					$args_num_property = "{$action_name}_args_num";
					
					$priority = isset( $class_instance->$priority_property ) ? $class_instance->$priority_property : 10;
					$args_num = isset( $class_instance->$args_num_property ) ? $class_instance->$args_num_property : 1;
					
					add_action( $action_name, array( & $class_instance, $action_name ), $priority, $args_num );
				}
			}
		}
	}
	
	/**
	 *	After Theme Setup
	 */
	public function afterSetupTheme() {
		// Theme Support
		add_theme_support( 'html5' );
		add_theme_support( 'menus' );
		add_theme_support( 'widgets' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'featured-image' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'post-formats', array( 'video', 'quote', 'image', 'link', 'gallery', 'audio' ) );
		add_theme_support( 'title-tag' );
	
		// Theme Textdomain
		load_theme_textdomain( 'kalium', get_template_directory() . '/languages' );
	
		// Register Menus
		register_nav_menus(
			array(
				'main-menu'   => 'Main Menu',
				'mobile-menu' => 'Mobile Menu',
			)
		);
		
		// Content Width
		$GLOBALS['content_width'] = apply_filters( 'kalium_content_width', 945 );
	}
	
	/**
	 *	Inintialize Admin Pages
	 */
	public function initAdminPages() {
		global $pagenow;
		
		// Script and styles
		add_action( 'admin_enqueue_scripts', array( & $this, 'adminPageEnqueue' ) );
		add_action( 'admin_print_styles', array( & $this, 'adminPagePrintStyles' ) );
		
		// Laborator Menu Page
		add_action( 'admin_menu', array( & $this, 'setupAdminMenu' ), 1 );
		add_action( 'admin_menu', array( & $this, 'sortLaboratorSubmenuItems' ), 100 );
		add_action( 'admin_head', array( & $this, 'hideLaboratorSubmenuItems' ), 100 );
		
		// Theme Options Redirect

		if ( 'themes.php' == $pagenow && 'theme-options' == kalium()->get( 'page' ) ) {
			wp_redirect( admin_url( "admin.php?page=laborator_options" ) );
			die();
		}
	}
	
	/**
	 *	Enqueue scripts for back-end part
	 */
	public function adminPageEnqueue() {
		wp_enqueue_script( 'admin-js' );
		wp_enqueue_style( 'admin-css' );
		?>
		<script type="text/javascript">var kaliumAssetsDir = "<?php echo esc_attr( $this->assetsUrl() ); ?>";</script>
		<?php
	}
	
	/**
	 *	Print styles in back-end part
	 */
	public function adminPagePrintStyles() {
		include kalium()->locateFile( 'inc/admin-tpls/admin-print-styles.php' );
	}
	
	/**
	 *	Laborator Admin Menu
	 */
	public function setupAdminMenu() {
		list( $plugin_updates, $updates_notification ) = kalium_get_plugin_updates_requires();
		
		// Main Laborator Menu Item
		add_menu_page( 'Laborator', 'Laborator', 'edit_theme_options', 'laborator_options', array( & $this, 'adminPageThemeOptions' ), '', 2 );
		
		// Updates Notification
		if ( $plugin_updates > 0 ) {
			add_submenu_page( 'laborator_options', 'Update Plugins', 'Update Plugins' . $updates_notification, 'edit_theme_options', 'kalium-install-plugins', 'laborator_null_function' ); 
		}
		
		// Demo Content Installer
		add_submenu_page( 'laborator_options', '1-Click Demo Content Installer', 'Demo Content Install', 'edit_theme_options', 'laborator-demo-content-installer', 'lab_1cl_demo_installer_page' );
		
		// Documentation Page iFrame
		add_submenu_page( 'laborator_options', 'Documentation', 'Theme Help', 'edit_theme_options', 'laborator-docs', array( & $this, 'adminPageDocumentation' ) );
		
		// About Kalium
		add_submenu_page( 'laborator_options', 'About', 'About', 'edit_theme_options', 'laborator-about', array( & $this, 'adminPageAboutKalium' ) );
		
		// Page redirects/shortcuts
		switch ( kalium()->get( 'page' ) ) {
			case 'browse-laborator-themes':
				wp_redirect( 'http://themeforest.net/user/Laborator/portfolio?ref=Laborator' );
				break;
		}
	}
	
	/**
	 *	Set order of Laborator menu items in admin panel
	 */
	public function sortLaboratorSubmenuItems() {
		global $submenu;
		
		$i = 0;
		
		$items_order = array(
			'laborator_options'                => ++$i,
			'kalium-product-registration'      => ++$i,
			'kalium-install-plugins'		   => ++$i,
			'typolab'                		   => ++$i,
			'laborator-demo-content-installer' => ++$i,
			'laborator-system-status'		   => ++$i,
			'laborator-docs'                   => ++$i,
			'laborator-about'		   		   => ++$i,
		);
		
		if ( isset( $submenu['laborator_options'] ) && is_array( $submenu['laborator_options'] ) ) {
		
			foreach ( $submenu['laborator_options'] as & $submenu_item ) {
				foreach ( $items_order as $submenu_link_id => $submenu_order_num ) {
					if ( $submenu_link_id == $submenu_item[2] ) {
						$submenu_item['order'] = $submenu_order_num;
					}
				}
			}
			
			usort( $submenu['laborator_options'], array( & $this, 'sortSubmenuItemsByOrder' ) );
		}
	}
	
	public function sortSubmenuItemsByOrder( $a, $b ) {
		if ( ! isset( $a['order'] ) || ! isset( $b['order'] ) ) {
			return 0;
		}
		
		return $a['order'] > $b['order'] ? 1 : -1;
	}
	
	/**
	 *	Hide items from Laborator Submenu in admin panel (when necessary)
	 */
	public function hideLaboratorSubmenuItems() {
		global $submenu;
		
		$hide_items = array();
		
		// Hide product registration when a valid license is provided
		if ( Kalium_Theme_License::isValid() ) {
			$hide_items[] = 'kalium-product-registration';
		}
		
		if ( isset( $submenu['laborator_options'] ) && is_array( $submenu['laborator_options'] ) ) {
			foreach ( $submenu['laborator_options'] as $i => $submenu_item ) {
				if ( in_array( $submenu_item[2], $hide_items ) ) {
					unset( $submenu['laborator_options'][ $i ] );
				}
			}
		}
		
		// Change name for Theme Options
		if ( isset( $submenu['laborator_options'] ) && is_array( $submenu['laborator_options'] ) ) {
			$submenu['laborator_options'][0][0] = 'Theme Options';
		}
	}
	
	/**
	 *	Theme Options
	 */
	public function adminPageThemeOptions() {
		global $options_machine;
		
		//include_once( ADMIN_PATH . 'front-end/options.php' );
		include kalium()->locateFile( 'inc/lib/smof/front-end/options.php' );
	}
	
	/**
	 *	About Kalium Theme
	 */
	public function adminPageAboutKalium() {
		include kalium()->locateFile( 'inc/admin-tpls/page-about-kalium.php' );
	}
	
	/**
	 *	Documentation Page
	 */
	public function adminPageDocumentation() {
		include kalium()->locateFile( 'inc/admin-tpls/page-theme-documentation.php' );
	}
	
	/**
	 *	Get current theme version
	 */
	public function getVersion( $strip_dots = false ) {
		if ( $strip_dots ) {
			return str_replace( '.', '', self::$version );
		}
		
		return self::$version;
	}
	
	/**
	 *	Get Theme Directory
	 */
	public function getThemeDir( $prepend = '' ) {
		return str_replace( ABSPATH, $prepend, kalium()->locateFile() );
	}
	
	/**
	 *	Get Start Time of Exection
	 */
	public function getStartTime() {
		return self::$start_time;
	}
}


// Kalium Base Functions
abstract class Kalium_Base {
	
	// GET request method
	public function get( $var, $isset = false ) {
		if ( ! isset( $_REQUEST[ $var ] ) ) {
			return null;
		}
		
		return $isset ? true : $_REQUEST[ $var ];
	}
	
	// POST request method
	public function post( $var ) {
		return isset( $_POST[ $var ] ) ? $_POST[ $var ] : null;
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


// Kalium instance shortcut
function kalium() {
	global $kalium;
	return $kalium;
}