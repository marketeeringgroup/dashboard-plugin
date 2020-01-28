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
        <?php submit_button(); ?>
    </form>
</div>