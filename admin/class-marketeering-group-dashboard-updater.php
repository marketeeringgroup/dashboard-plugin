<?php
/**
 *
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
    private $username;
    private $repository;
    private $authorize_token;
    private $github_response;

    public function __construct($marketeering_group_dashboard, $version, $file)
    {
        $this->marketeering_group_dashboard = $marketeering_group_dashboard;
        $this->version = $version;
        $this->file = $file;
        return $this;
    }

    public function set_plugin_properties()
    {
        $this->basename = plugin_basename(MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR);
        $this->plugin   = get_plugin_data(MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR . $this->basename . '.php');
        $this->active   = is_plugin_active(MARKETEERING_GROUP_DASHBOARD_PLUGIN_MAIN_FILE);
    }

    public function set_username($username)
    {
        $this->username = $username;
    }

    public function set_repository($repository)
    {
        $this->repository = $repository;
    }

    public function authorize($token)
    {
        $this->authorize_token = $token;
    }

    private function get_repository_info()
    {
        if (is_null($this->github_response)) { // Do we have a response?
            $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository); // Build URI
            
            $args = array(); 
            
            if ($this->authorize_token) { // Is there an access token?
                // add access token to header
                $args = array(
                    'headers' => array(
                        'Authorization' => 'bearer ' . $this->authorize_token,
                    )
                );
            }
            
            $response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri, $args)), true); // Get JSON and parse it
            // if (is_array($response)) { // If it is an array
            //     $response = current($response); // Get the first item
            // }
            
            if ($this->authorize_token) { // Is there an access token?
                $response['zipball_url'] = add_query_arg('access_token', $this->authorize_token, $response['zipball_url']); // Update our zip url with token
            }
            
            $this->github_response = $response; // Set it to our property  
        }
    }

    public function initialize()
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'modify_transient'), 10, 1);
        add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
        add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
    }

    public function modify_transient($transient)
    {

        if (property_exists($transient, 'checked')) { // Check if transient has a checked property
            if ($transient->checked) { // Did WordPress check for updates?
                $this->get_repository_info(); // Get the repo info
                $out_of_date = version_compare($this->github_response['tag_name'], $this->version, 'gt'); // Check if we're out of date
                if ($out_of_date) {
                    $new_files = $this->github_response['zipball_url']; // Get the ZIP
                    $slug = current(explode('/', $this->basename)); // Create valid slug
                    // $slug = $this->basename;
                    $plugin = array( // setup our plugin info
                        'url' => $this->plugin["PluginURI"],
                        'slug' => $slug,
                        'package' => $new_files,
                        'new_version' => $this->github_response['tag_name']
                    );
                    $transient->response[MARKETEERING_GROUP_DASHBOARD_PLUGIN_MAIN_FILE] = (object) $plugin; // Return it in response
                }
            }
        }
        return $transient; // Return filtered transient
    }

    public function plugin_popup($result, $action, $args)
    {
        if (!empty($args->slug)) { // If there is a slug
            if ($args->slug == current(explode('/', $this->basename))) { // And it's our slug
                $this->get_repository_info(); // Get our repo info
                // Set it to an array
                $plugin = array(
                    'name'              => $this->plugin["Name"],
                    'slug'              => $this->basename,
                    'version'           => $this->github_response["tag_name"],
                    'author'            => $this->plugin["AuthorName"],
                    'author_profile'    => $this->plugin["AuthorURI"],
                    'last_updated'      => $this->github_response["published_at"],
                    'homepage'          => $this->plugin["PluginURI"],
                    'short_description' => $this->plugin["Description"],
                    'sections'          => array(
                        'Description'   => $this->plugin["Description"],
                        'Updates'       => $this->github_response["body"],
                    ),
                    'download_link'     => $this->github_response["zipball_url"]
                );
                return (object) $plugin; // Return the data
            }
        }
        return $result; // Otherwise return default
    }

    public function after_install($response, $hook_extra, $result)
    {
        global $wp_filesystem; // Get global FS object

        $install_directory = MARKETEERING_GROUP_DASHBOARD_PLUGIN_DIR; // Our plugin directory 
        $wp_filesystem->move($result['destination'], $install_directory); // Move files to the plugin dir
        $result['destination'] = $install_directory; // Set the destination for the rest of the stack

        if ($this->active) { // If it was active
            activate_plugin($this->basename); // Reactivate
        }
        return $result;
    }
}
