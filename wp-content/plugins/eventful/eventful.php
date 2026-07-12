<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeatelier.net
 * @since             1.0.0
 * @package           Eventful
 *
 * @wordpress-plugin
 * Plugin Name:       Eventful
 * Plugin URI:        https://wpeventful.com/
 * Description:       Showcase events from The Events Calendar in stunning sliders, grids, carousels, and lists — fully customizable and builder-friendly.
 * Version:           2.2.5
 * Author:            ThemeAtelier
 * Author URI:        https://themeatelier.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Tested up to:      7.0
 * Text Domain:       eventful
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

require __DIR__ . '/vendor/autoload.php';

use ThemeAtelier\Eventful\Includes\Eventful;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if (! defined('EVENTFUL_VERSION')) {
	define('EVENTFUL_VERSION', '2.2.5');
}
if (! defined('EVENTFUL_PLUGIN_NAME')) {
	define('EVENTFUL_PLUGIN_NAME', 'eventful');
}
if (!defined('EVENTFUL_FILE')) {
	define('EVENTFUL_FILE', __FILE__);
}
if (!defined('EVENTFUL_BASENAME')) {
	define('EVENTFUL_BASENAME', plugin_basename(__FILE__));
}
if (!defined('EVENTFUL_DIR_PATH')) {
	define('EVENTFUL_DIR_PATH', plugin_dir_path(__FILE__));
}
if (!defined('EVENTFUL_DIR_URL')) {
	define('EVENTFUL_DIR_URL', plugin_dir_url(__FILE__));
}
if (!defined('EVENTFUL_DIR_URL_ADMIN')) {
	define('EVENTFUL_DIR_URL_ADMIN', EVENTFUL_DIR_URL . 'src/Admin/');
}
if (!defined('EVENTFUL_PATH')) {
	define('EVENTFUL_PATH', dirname(EVENTFUL_FILE));
}
if (!defined('EVENTFUL_DIR_NAME')) {
	define('EVENTFUL_DIR_NAME', dirname(__FILE__));
}
if (!defined('EVENTFUL_PLUGINS_URL')) {
	define('EVENTFUL_PLUGINS_URL', plugins_url('', __FILE__));
}
if (!defined('EVENTFUL_DEMO_URL')) {
	define('EVENTFUL_DEMO_URL', 'https://wpeventful.com/');
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function eventful_run()
{
	$plugin = new Eventful();
	$plugin->run();
}

/**
 * Pro version check.
 *
 * @return boolean
 */
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if (! (is_plugin_active('eventful-pro/eventful-pro.php') || is_plugin_active_for_network('eventful-pro/eventful-pro.php'))) {
	eventful_run();
}


// Appsero init
/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function eventful_appsero_init_tracker()
{
	if (!class_exists('EventfulAppSero\Insights')) {
		require_once  EVENTFUL_DIR_PATH . 'src/Admin/appsero/Client.php';
	}
	$client = new EventfulAppSero\Client('82e15bff-56c3-4809-bee3-51d69e047387', 'Eventful Events Showcase', __FILE__);
	// Active insights
	$client->insights()->init();
}

eventful_appsero_init_tracker();
