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
    <h2>My Plugin Page Title</h2>
    <form method="post" action="options.php">
        <?php settings_fields('myplugin_options_group'); ?>
        <h3>This is my option</h3>
        <p>Some text here.</p>
        <table>
            <tr valign="top">
                <th scope="row"><label for="myplugin_option_name">Label</label></th>
                <td><input type="text" id="myplugin_option_name" name="myplugin_option_name" value="<?php echo get_option('myplugin_option_name'); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
