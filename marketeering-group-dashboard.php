<?php
/**
 * Plugin Name:       Marketeering Group Dashboard
 * Plugin URI:        https://github.com/marketeeringgroup/dashboard-plugin
 * Description:       This plugin customizes and cleans up the WordPress backend.
 * Version:           1.2.1
 * Author:            Marketeering Group
 * Author URI:        https://marketeeringgroup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       marketeering-group-dashboard
 * Domain Path:       /languages
 * 
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://marketeeringgroup.com
 * @since             1.0.0
 * @package           Marketeering_Group_Dashboard
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MARKETEERING_GROUP_DASHBOARD_VERSION', '1.3' );
define( 'MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define( 'MARKETEERING_GROUP_DASHBOARD_PLUGIN_MAIN_FILE', 'marketeering-group-dashboard/marketeering-group-dashboard.php');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-marketeering-group-dashboard-activator.php
 */
function activate_marketeering_group_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-marketeering-group-dashboard-activator.php';
	Marketeering_Group_Dashboard_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-marketeering-group-dashboard-deactivator.php
 */
function deactivate_marketeering_group_dashboard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-marketeering-group-dashboard-deactivator.php';
	Marketeering_Group_Dashboard_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_marketeering_group_dashboard' );
register_deactivation_hook( __FILE__, 'deactivate_marketeering_group_dashboard' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-marketeering-group-dashboard.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_marketeering_group_dashboard() {

	$plugin = new Marketeering_Group_Dashboard();
	$plugin->run();

}
run_marketeering_group_dashboard();
