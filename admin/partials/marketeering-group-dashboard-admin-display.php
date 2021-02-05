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
    <?php //screen_icon(); ?>
    <h1>Marketeering Group Dashboard Options</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('mgdashboard_options_group');
        $turn_comments_off = get_option('turn_comments_off') === "on" ? 'checked' : '';
        $enable_staging_notice = get_option('enable_staging_notice') === "on" ? 'checked' : '';
        ?>
        <p>Select the image to be used on the WordPress Login screen.</p>
        <table>
            <tr valign="center">
                <th scope="row"><label for="login_logo_url">Login Logo</label></th>
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
        <h2>Admin Notices</h2>
        <p><input id="enable_staging_notice" type="checkbox" name="enable_staging_notice" <?php echo $enable_staging_notice; ?> /><label for="enable_staging_notice">Enable Staging Site Notice</label></p>
        <hr>
        <h2>Comments</h2>
        <p><input id="turn_comments_off" type="checkbox" name="turn_comments_off" <?php echo $turn_comments_off; ?> /><label for="turn_comments_off">Disable Comments</label></p>
        <hr>
        <h2>Hide Additional Menu Items</h2>
        <p><label for="hidden_menu_items">Menu Items to Hide for <strong>ALL</strong> users - Separate each URL with a |</label><br />
            <textarea id="hidden_menu_items" class="mg-settings-textbox" name="hidden_menu_items"><?php echo get_option('hidden_menu_items'); ?></textarea></p>

        <p><label for="hidden_editor_menu_items">Menu Items to Hide for <strong>EDITOR</strong> users - Separate each URL with a |</label><br />
            <textarea id="hidden_editor_menu_items" class="mg-settings-textbox" name="hidden_editor_menu_items"><?php echo get_option('hidden_editor_menu_items'); ?></textarea></p>
        <hr>
        <h2>Google Analytics/Tag Manager</h2>
        <p><label for="analytics_head">Head</label><br />
            <textarea id="analytics_head" class="mg-settings-textbox" name="analytics_head"><?php echo get_option('analytics_head'); ?></textarea></p>

        <p><label for="analytics_body">Body</label><br />
            <textarea id="analytics_body" class="mg-settings-textbox" name="analytics_body"><?php echo get_option('analytics_body'); ?></textarea></p>

        <?php submit_button(); ?>
    </form>
</div>