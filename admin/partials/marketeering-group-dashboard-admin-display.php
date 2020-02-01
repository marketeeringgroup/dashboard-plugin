<?php

/**
 * Plugin Name:       Marketeering Group Dashboard
 * Plugin URI:        https://github.com/marketeeringgroup/dashboard-plugin
 * Description:       This plugin modifies the WordPress backend for client users with the Editor role.
 * Version:           1.0.0
 * Author:            Marketeering Group
 * Author URI:        https://marketeeringgroup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       marketeering-group-dashboard
 * Domain Path:       /languages
 */
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://marketeeringgroup.com
 * @since      1.0.0
 *
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div>
    <?php screen_icon(); ?>
    <h1>Marketeering Group Dashboard Options</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('mgdashboard_options_group');
        $turn_comments_off = get_option('turn_comments_off') === "on" ? 'checked' : '';
        ?>
        <p>Select the image to be used on the WordPress Login screen.</p>
        <table>
            <tr valign="center">
                <th scope="row"><label for="login_logo_url">Login Logo URL</label></th>
                <td><input id="login_logo_url" type="text" name="login_logo_url" value="<?php echo get_option('login_logo_url'); ?>" />
                    <input id="upload_image_button" type="button" class="button-primary" value="Insert Image" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php if (get_option('login_logo_url')) {
                        echo '<img id="login_logo" src="' . get_option('login_logo_url') . '" />';
                    } else {
                        echo 'No image selected';
                    } ?>
                </td>
            </tr>
        </table>
        <hr>
        <h2>Comments</h2>
        <p><input id="turn_comments_off" type="checkbox" name="turn_comments_off" <?php echo $turn_comments_off; ?> /><label for="turn_comments_off">Disable Comments</label></p>
        <hr>
        <h2>Hide Additional Menu Items</h2>
        <label for="hidden_menu_items">Menu Items to Hide - Separate each URL with a |</label><br/>
        <textarea id="hidden_menu_items" name="hidden_men u_items"><?php echo get_option('hidden_menu_items'); ?></textarea>

        <?php

        // $response = add_query_arg('access_token', 'd4fd6f81cf036b1f8ed5839476c43a080bb5ba0d', 'https://api.github.com/repos/marketeeringgroup/dashboard-plugin/zipball/1.1.0');
        $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', 'marketeeringgroup', 'dashboard-plugin'); // Build URI

        // if ($this->authorize_token) { // Is there an access token?
            //$request_uri = add_query_arg('access_token', $this->authorize_token, $request_uri); // Append it
            $args = array(
                'headers' => array(
                    'Authorization' => 'bearer ' . 'd4fd6f81cf036b1f8ed5839476c43a080bb5ba0d',
                )
            );
        // }

        $response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri, $args)), true);
        $slug = current(explode('/', plugin_basename(__FILE__)));
        $slug = explode('/', plugin_basename(__FILE__));

        $plugin_path = MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR . $slug[0] . '.php';
        $plugin = get_plugin_data(__FILE__);

        $basename = plugin_basename(MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR);
        //$basename = plugin_basename(__FILE__);

        $is_active = is_plugin_active($basename . '/' . $basename . '.php');
        echo '<pre>';
        var_dump($is_active, $basename, $response, $plugin_path, $plugin);
        echo '</pre>';

        ?>
        <?php submit_button(); ?>
    </form>
</div>