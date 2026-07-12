<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful
 * @subpackage eventful/Admin
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin;

/**
 * The admin class
 */
class DBUpdates
{
    /**
     * DB updates that need to be run
     *
     * @var array
     */
    private static $updates = array(
        '2.0.0' => 'updates/update-2.0.0.php',
        '2.2.0' => 'updates/update-2.2.0.php',
    );
    
    /**
     * The class constructor.
     *
     */
    function __construct()
    {
        add_action('plugins_loaded', array($this, 'perform_updates'));
    }

    /**
     * Check if an update is needed.
     *
     * @return bool
     */
    public function is_needs_update() {
        $installed_version = get_option('eventful_version');
        $first_version     = get_option('eventful_first_version');
        $activation_date   = get_option('eventful_activation_date');

        if (false === $installed_version) {
            update_option('eventful_version', '2.0.6');
        }
        if (false === $first_version) {
            update_option('eventful_first_version', EVENTFUL_VERSION);
        }
        if (false === $activation_date) {
            update_option('eventful_activation_date', time());
        }

        if (version_compare($installed_version, EVENTFUL_VERSION, '<')) {
            return true;
        }

        return false;
    }

    /**
     * Perform all updates.
     *
     */
    public function perform_updates() {
        if (!$this->is_needs_update()) {
            return;
        }

        $installed_version = get_option('eventful_version');

        foreach (self::$updates as $version => $path) {
            if (version_compare($installed_version, $version, '<')) {
                include __DIR__ . '/' . $path;
                update_option('eventful_version', $version);
            }
        }

        update_option('eventful_version', EVENTFUL_VERSION);
    }
}
