<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://marketeeringgroup.com
 * @since      1.0.0
 *
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/admin
 * @author     Your Name <email@example.com>
 */
class Marketeering_Group_Dashboard_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $marketeering_group_dashboard    The ID of this plugin.
	 */
	private $marketeering_group_dashboard;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $marketeering_group_dashboard       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($marketeering_group_dashboard, $version)
	{

		$this->marketeering_group_dashboard = $marketeering_group_dashboard;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Marketeering_Group_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Marketeering_Group_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->marketeering_group_dashboard, plugin_dir_url(__FILE__) . 'css/marketeering-group-dashboard-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Marketeering_Group_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Marketeering_Group_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// WordPress Media Scripts
		wp_enqueue_media();

		wp_enqueue_script($this->marketeering_group_dashboard, plugin_dir_url(__FILE__) . 'js/marketeering-group-dashboard-admin.js', array('jquery'), $this->version, true);

	}

	public function add_editor_capability()
	{
		/**
		 * Adds access to the appearance menu for the Editor role
		 */

		$role = get_role('editor');
		if ($role->capabilities["edit_theme_options"]) return;
		$role->add_cap('edit_theme_options');
	}

	public function remove_admin_bar_items() 
	{
		/**
		 * Removes WordPress logo from Admin bar
		 */

		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');

		if (current_user_can('editor')) {
			$wp_admin_bar->remove_menu('simple-history-view-history');
		}
	}

	public function add_admin_bar_items() 
	{

		global $wp_admin_bar;

		$wp_admin_bar->add_node(array(
			'id'    => 'mg-support-link',
			'title' => 'Contact Tech Support',
			'href'  => 'mailto:siteupdates@marketeeringgroup.com',
			'meta'  => array(
				'title' => __('Contact Tech Support'),
			),
		));
	}

	public function hide_menus()
	{
		/**
		 * Hides unnecessary menu options
		 */

		// Hide comments menu item for all users if setting is checked
		$turn_comments_off = get_option('turn_comments_off') === "on" ? true : false;
		
		if ($turn_comments_off) {
			remove_menu_page('edit-comments.php');									// Comments
			remove_submenu_page('options-general.php', 'options-discussion.php');	// Settings > Discussion
		}

		// Hide user-submitted menu items for all users
		$menu_items = get_option('hidden_menu_items');
		$menu_items = explode("|", $menu_items);

		foreach ($menu_items as $menu_item) {
			$menu_item = trim($menu_item);
			remove_menu_page($menu_item);
		}

		// Hide menu items for Editor role
		$editor_menu_items = get_option('hidden_editor_menu_items');
		$editor_menu_items = explode("|", $editor_menu_items);

		if (current_user_can('editor')) {

			// main menu items
			remove_menu_page('tools.php');
			remove_menu_page('vc-welcome');

			// user-specified menu items
			foreach ($editor_menu_items as $editor_menu_item) {
				$editor_menu_item = trim($editor_menu_item);
				remove_menu_page($editor_menu_item);
			}

			// submenu items
			remove_submenu_page('themes.php', 'themes.php');

		}
	}

	function remove_comment_support()
	{
		/**
		 * Removes support for Comments
		 * 
		 */
		
		remove_post_type_support('post', 'comments');
		remove_post_type_support('page', 'comments');
	}

	function remove_comments_admin_bar()
	{
		/**
		 * Removes Comments icon in Admin Bar
		 * 
		 */

		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('comments');
	}

	public function remove_dashboard_meta()
	{
		/**
		 * Removes unnecessary dashboard widgets
		 */

		// removes widgets for all users
		remove_meta_box('dashboard_activity', 'dashboard', 'normal'); 					// Activity
		remove_meta_box('dashboard_primary', 'dashboard', 'side'); 						// WP Events & News
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); 					// At a Glance
		remove_meta_box('wpe_dify_news_feed', 'dashboard', 'normal'); 					// WP Engine 
		remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); 			// Yoast SEO 
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); 					// Quick Press 

		// removes widgets for non-admin users
		if (!current_user_can('manage_options')) {

			remove_meta_box('simple_history_dashboard_widget', 'dashboard', 'normal'); 	// Simple History

		}
	}

	public function add_custom_dashboard_widgets()
	{
		/**
		 * Adds Marketeering Group customer support widget
		 * 
		 */
		wp_add_dashboard_widget(
			'marketeering_dashboard_widget', 	// Widget slug.
			'Marketeering Group Support', 		// Title.
			'custom_dashboard_widget_content' 	// Display function.
		);

		function custom_dashboard_widget_content()
		{

			echo "<h3>Welcome to your WordPress Dashboard!</h3><p>Here you can add and edit pages, blog posts and menus. Check out the links below for a few video tutorials on working in WordPress.</p><p>If you ever need assistance with edits, don't hesitate to contact the Marketeering Group Development Team! Simply send a message to <a href='mailto:siteupdates@markteeringgroup.com'>siteupdates@markteeringgroup.com</a>, and we'll be happy to help you out!</p><h4>Tutorial Links</h4><ul><li><strong>Writing Blog Posts in WordPress:</strong> <a href='https://www.youtube.com/watch?v=rld_XRvAOfs' target='_blank'>WordPress Gutenberg Editor - Quick Start Tutorial</a></li><li><strong>Editing Pages with WPBakery Page Builder:</strong> <a href='https://www.youtube.com/watch?v=Vp7TaaJZKeU' target='_blank'>WPBakery Page Builder Beginners Guide</a></li><li><strong>Adding Links to the Menu:</strong> <a href='https://www.youtube.com/watch?v=ZzNwCHG_VWk' target='_blank'>How to Add Navigation Menu in WordPress</a></li></ul>";
		}
	}

	public function register_settings()
	{
		/**
		 * creates options for settings page
		 * 
		 * adds a new option
		 * add_option( 
		 * 		string 		$option_name, 
		 * 		mixed 		$value = '', 
		 * 		string 		$deprecated = '', 
		 * 		string|bool $autoload = 'yes' )
		 * 
		 * defines a setting to create an entry in the wp_options table
		 * register_setting( 
		 * 		string 		$option_group, 
		 * 		string 		$option_name, 
		 * 		array 		$args = array() )
		 */
		
		// Login Logo
		add_option('login_logo_url');
		register_setting('mgdashboard_options_group', 'login_logo_url');

		// Show/Hide Comment Menu Item
		add_option('turn_comments_off');
		register_setting('mgdashboard_options_group', 'turn_comments_off');
		
		// Menu Items to Hide for all users
		add_option('hidden_menu_items');
		register_setting('mgdashboard_options_group', 'hidden_menu_items');
		
		// Menu Items to Hide for Editors
		add_option('hidden_editor_menu_items');
		register_setting('mgdashboard_options_group', 'hidden_editor_menu_items');

	}

	public function register_options_page()
	{
		/**
		 * Creates settings page view
		 * 
		 * Add submenu page to the Settings main menu.
		 * add_options_page( 
		 * 		string 		$page_title, 
		 * 		string 		$menu_title, 
		 * 		string 		$capability, 
		 * 		string 		$menu_slug, 
		 * 		callable 	$function = '', 
		 * 		int 		$position = null )
		 */

		add_options_page('Marketeering Group', 'MG Dashboard', 'manage_options', 'marketeering-group', 'mgdashboard_options_page');

		function mgdashboard_options_page()
		{

			// the settings page view
			include 'partials/marketeering-group-dashboard-admin-display.php';
		}
	}

	public function custom_login_logo()
	{ 
		/**
		 * Adds custom logo to login screen 
		 * 
		 * */	
	?>
		<style type="text/css">
			#login h1 a,
			.login h1 a {
				background-image: url(<?php echo get_option('login_logo_url'); ?>);
				padding-bottom: 30px;
				width: 100%;
				background-size: contain;
			}
		</style>
	<?php
	}
	
}
