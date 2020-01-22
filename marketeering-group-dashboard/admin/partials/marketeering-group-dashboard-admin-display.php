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
        <h3>This is my option</h3>
        <p>Some text here.</p>
        <table>
            <tr valign="top">
                <th scope="row"><label for="mgdashboard_option_name">Label</label></th>
                <td><input type="text" id="mgdashboard_option_name" name="mgdashboard_option_name" value="<?php echo get_option('mgdashboard_option_name'); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
