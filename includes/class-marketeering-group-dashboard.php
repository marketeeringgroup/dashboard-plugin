<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://marketeeringgroup.com
 * @since      1.0.0
 *
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/includes
 * @author     Your Name <email@example.com>
 */
class Marketeering_Group_Dashboard {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Marketeering_Group_Dashboard_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $marketeering_group_dashboard    The string used to uniquely identify this plugin.
	 */
	protected $marketeering_group_dashboard;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MARKETEERING_GROUP_DASHBOARD_VERSION' ) ) {
			$this->version = MARKETEERING_GROUP_DASHBOARD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->marketeering_group_dashboard = 'marketeering-group-dashboard';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->check_updates();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Marketeering_Group_Dashboard_Loader. Orchestrates the hooks of the plugin.
	 * - Marketeering_Group_Dashboard_i18n. Defines internationalization functionality.
	 * - Marketeering_Group_Dashboard_Admin. Defines all hooks for the admin area.
	 * - Marketeering_Group_Dashboard_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-marketeering-group-dashboard-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-marketeering-group-dashboard-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-marketeering-group-dashboard-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-marketeering-group-dashboard-public.php';

		/**
		 * The class responsible for defining all actions that occur in the updater area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-marketeering-group-dashboard-updater.php';

		$this->loader = new Marketeering_Group_Dashboard_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Marketeering_Group_Dashboard_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Marketeering_Group_Dashboard_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Marketeering_Group_Dashboard_Admin( $this->get_marketeering_group_dashboard(), $this->get_version() );

		// enqueue scripts and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// modify capabilities and views
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_editor_capability' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_seo_manager_capability' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'hide_menus' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'remove_dashboard_meta' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'add_custom_dashboard_widgets' );
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'remove_admin_bar_items' );
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'add_admin_bar_items', 9999 );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'modify_footer_text' );

		// add dashboard notice
		if ( get_option( 'enable_staging_notice' )) {
			$this->loader->add_action('admin_notices', $plugin_admin, 'add_staging_site_warning');
		}

		// create settings page
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_options_page' );

		// modify WordPress logo if option is set
		if ( get_option( 'login_logo_url' )) {
			$this->loader->add_action('login_enqueue_scripts', $plugin_admin, 'custom_login_logo' );
		}

		// disable comments if option is set
		if ( get_option( 'turn_comments_off' )) {
			$this->loader->add_action('init', $plugin_admin, 'remove_comment_support', 100);
			$this->loader->add_action('wp_before_admin_bar_render', $plugin_admin, 'remove_comments_admin_bar', 100);
		}

		// add google analytics/tag manager code to head and body
		if (get_option('analytics_head')) {
			$this->loader->add_action('wp_head', $plugin_admin, 'add_analytics_head');
		}

		if (get_option('analytics_body')) {
			$this->loader->add_action('wp_body_open', $plugin_admin, 'add_analytics_body');
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Marketeering_Group_Dashboard_Public( $this->get_marketeering_group_dashboard(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Check for updates to plugin on Github Repository
	 * 
	 * @since 	1.2.0
	 * @access	private
	 */
	private function check_updates() {

		$plugin_updater = new Marketeering_Group_Dashboard_Updater($this->get_marketeering_group_dashboard(), $this->get_version(), MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR);
		
		$this->loader->add_action('admin_init', $plugin_updater, 'set_plugin_properties');

		$plugin_updater->set_username('marketeeringgroup'); 						// set username
		$plugin_updater->set_repository('dashboard-plugin'); 						// set repo
		$plugin_updater->initialize(); 	
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_marketeering_group_dashboard() {
		return $this->marketeering_group_dashboard;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Marketeering_Group_Dashboard_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}



}
