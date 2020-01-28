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
 * The updater functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Marketeering_Group_Dashboard
 * @subpackage Marketeering_Group_Dashboard/admin
 * @author     Your Name <email@example.com>
 */

class Marketeering_Group_Dashboard_Updater
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

    protected $file;
    protected $plugin;
    protected $basename;
    protected $active;

    public function __construct($marketeering_group_dashboard, $version, $file)
    {
        $this->marketeering_group_dashboard = $marketeering_group_dashboard;
        $this->version = $version;

        $this->file = $file;
        return $this;
    }

    public function set_plugin_properties()
    {
        $this->plugin   = get_plugin_data($this->file);
        $this->basename = plugin_basename($this->file);
        $this->active   = is_plugin_active($this->basename);
    }
}