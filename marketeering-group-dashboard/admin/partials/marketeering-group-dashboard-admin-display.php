<?php

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
        <?php settings_fields('mgdashboard_options_group'); ?>
        <p>Add the URL of the logo to be used on the WordPress login screen.</p>
        <table>
            <tr valign="top">
                <th scope="row"><label for="login_logo_url">Login Logo URL</label></th>
                <td><input type="text" id="login_logo_url" name="login_logo_url" value="<?php echo get_option('login_logo_url'); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>